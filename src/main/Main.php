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
     * OSごとのコマンド名解決
     *
     * @param CommandQuery $query コマンド命令
     * @return CommandQuery 解決後コマンド命令
     */
    public function resolveCommand(CommandQuery $query): CommandQuery {
        $cmd = $query->cmd;
        $args = $query->args;

        // Windowsの場合はbat, cmdを付加する
        if (PHP_OS_FAMILY == "Windows") {
            $basename = pathinfo($cmd, PATHINFO_EXTENSION);

            // 拡張子なし
            if ($basename === "") {
                if (file_exists($cmd . '.bat'))
                    $cmd = "\"./" . $cmd . '.bat' . "\"";

                if (file_exists($cmd . '.cmd'))
                    $cmd = "\"./" . $cmd . '.cmd' . "\"";

                $query = new CommandQuery($cmd, $args);
            }
        }

        return $query;
    }

    /**
     * タスクを実行する
     *
     * @param Task $task タスク
     * @return int 終了コード
     * @throws CommandExecuteException
     */
    public function runTask(Task $task): int {

        while ($query = $task->next()) {
            $query = $this->resolveCommand($query);

            $exitCode = $this->executor->execute($query);

            // 異常終了の場合はそのまま異常終了とする
            if ($exitCode !== 0)
                return $exitCode;
        }

        return 0;
    }
}
