<?php
namespace Pitan76\Todofile;

use Exception;
use Pitan76\Todofile\Command\Commands;
use Pitan76\Todofile\Exception\CommandExecuteException;
use Pitan76\Todofile\Exception\TaskNotFoundException;
use Symfony\Component\Console\Application;

class Main {

    /**
     * @var Application symfony/console CLIのアプリケーション
     */
    public Application $app;

    /**
     * @var CommandExecutor コマンド実行するクラス
     */
    public CommandExecutor $executor;

    /**
     * タスクファイル名
     */
    public const string FILENAME = "todo.json";

    public static Main $INSTANCE;

    public function __construct() {
        // CLIアプリケーション初期化
        $this->app = new Application('Todofile');
        $this->executor = new CommandExecutor();

        self::$INSTANCE = $this;

        // コマンド登録
        Commands::init();
    }

    /**
     * Todofileを実行する
     *
     * @return int 終了コード
     * @throws Exception
     */
    public function run(): int {
        global $argv;

        $taskName = $argv[1] ?? null;
        if ($taskName === null) {
            $this->app->getHelp();
            return 0;
        }

        $task = $this->parseTask($taskName);

        return $this->runTask($task);

//        return $this->app->run();
    }


    /**
     * タスクファイルを読み込んでタスクを作成する
     *
     * @param string $taskName タスク名
     * @return Task タスク
     * @throws TaskNotFoundException
     */
    public function parseTask(string $taskName): Task {
        // タスクファイルの読み込み
        $data = json_decode(file_get_contents(self::FILENAME), true);

        $commands = $data[$taskName] ?? null;

        // タスクが存在しない
        if ($commands === null) throw new TaskNotFoundException($taskName);

        // 文字列である場合は配列にする
        if (is_string($commands))
            $commands = [$commands];

        $queries = [];
        for ($i = 0; $i < count($commands); $i++) {
            $queries[$i] = CommandQuery::fromString($commands[$i]);
        }

        return new Task($taskName, $queries);
    }

    /**
     * @throws Exception
     */
    public function runTask(Task $task): int {

        while ($query = $task->next()) {
            $cmd = $query->cmd;
            $args = $query->args;

            if (PHP_OS_FAMILY == "Windows") {
                sapi_windows_vt100_support(STDOUT, true);
                sapi_windows_vt100_support(STDERR, true); // エラー出力用

                // Windowsの場合はbat, cmdを付加する (なお、今後この処理は切り出すべき)

                // 拡張子なし
                if (pathinfo($cmd, PATHINFO_EXTENSION) === '') {

                    if (file_exists($cmd . '.bat'))
                        $cmd = "\"./" . $cmd . '.bat' . "\"";

                    else if (file_exists($cmd . '.cmd'))
                        $cmd = "\"./" . $cmd . '.cmd' . "\"";

                    $query = new CommandQuery($cmd, $args);
                }
            }

            $exitCode = $this->executor->execute($query);

            // 異常終了の場合はそのまま異常終了とする
            if ($exitCode !== 0)
                return $exitCode;
        }

        return 0;
    }
}
