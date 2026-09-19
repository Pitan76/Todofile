<?php
namespace Pitan76\Todofile;

use ColinODell\Json5\SyntaxError;
use Exception;
use Pitan76\Todofile\BuildinCommand\Commands;
use Pitan76\Todofile\Command\CommandExecutor;
use Pitan76\Todofile\Command\CommandQuery;
use Pitan76\Todofile\Config\Config;
use Pitan76\Todofile\Exception\CommandExecuteException;
use Pitan76\Todofile\Exception\TodoFileNotFoundException;
use Pitan76\Todofile\Exception\UnsupportedExtensionException;
use Pitan76\Todofile\Task\Task;
use Pitan76\Todofile\Task\TaskParser;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class Main {

    public static Main $INSTANCE;
    public static Config $config;

    public Application $app; // symfony/console CLIのアプリケーション
    public ?ArgvInput $input = null; // 引数、オプションなど

    public CommandExecutor $executor;
    public TaskParser $taskParser;
    public TodofileLoader $todofileLoader;

    /**
     * @param array|null $argv
     * @throws SyntaxError | TodoFileNotFoundException | UnsupportedExtensionException
     */
    public function __construct(?array $argv = null) {
        // CLIアプリケーション初期化
        $this->app = new Application('Todofile');

        if ($argv !== null) {
            $def = new InputDefinition([
                new InputArgument('task', InputArgument::OPTIONAL),
                new InputOption('file', 'f', InputOption::VALUE_REQUIRED),
                new InputOption('version', 'v', InputOption::VALUE_NONE),
            ]);
            $this->input = new ArgvInput($argv, $def);

            if ($this->input->getOption("version")) {
                echo "v0.0.6";
                exit(0);
            }
        }

        $this->executor = new CommandExecutor();
        $this->todofileLoader = new TodofileLoader($this->getOption("file"));
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
        $taskName = $this->getInput()->getArgument("task");

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

    /**
     * @param string $name オプション名
     * @return mixed オプション値
     */
    public function getOption(string $name): mixed {
        if ($this->getInput() === null || !$this->getInput()->hasOption($name))
            return null;

        return $this->getInput()->getOption($name);
    }

    /**
     * @return ArgvInput|null 引数、オプション入力のオブジェ
     */
    public function getInput(): ?ArgvInput {
        return $this->input;
    }

    /**
     * @return Application CLIアプリ
     */
    public function getApp(): Application {
        return $this->app;
    }

}
