<?php
namespace Pitan76\Todofile;

use Exception;
use Pitan76\Todofile\BuildinCommand\Commands;
use Pitan76\Todofile\Command\CommandExecutor;
use Pitan76\Todofile\Command\CommandQuery;
use Pitan76\Todofile\Config\Config;
use Pitan76\Todofile\Exception\CommandExecuteException;
use Pitan76\Todofile\Task\Task;
use Pitan76\Todofile\Task\TaskParser;
use Symfony\Component\Console\Application;

class Main {

    /**
     * タスクファイル名
     */
    public const string FILENAME = "todo.json";

    public static Main $INSTANCE;

    public static Config $config;

    // symfony/console CLIのアプリケーション
    public Application $app;
    public CommandExecutor $executor;
    public TaskParser $taskParser;
    public TodofileLoader $todofileLoader;

    public function __construct() {
        // CLIアプリケーション初期化
        $this->app = new Application('Todofile');
        $this->executor = new CommandExecutor();
        $this->todofileLoader = new TodofileLoader(self::FILENAME);
        $this->taskParser = new TaskParser($this->todofileLoader);

        self::$config = new Config($this->todofileLoader);

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

        if ($taskName === null || $taskName[0] === "!" ) {
            $this->app->run();
            return 0;
        }

        $task = $this->taskParser->parse($taskName);

        return $this->runTask($task);
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

            // コマンド実行
            $exitCode = $this->executor->execute($query);

            // 異常終了の場合はそのまま異常終了とする
            if ($exitCode !== 0)
                return $exitCode;
        }

        return 0;
    }
}
