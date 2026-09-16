<?php
namespace Pitan76\Todofile;

use Exception;
use Pitan76\Todofile\Command\Commands;
use Symfony\Component\Console\Application;

class Main {

    /**
     * symfony/console CLIのアプリケーション
     */
    public Application $app;

    public static Main $INSTANCE;

    public function __construct() {
        // CLIアプリケーション初期化
        $this->app = new Application('Todofile');
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
        return $this->app->run();
    }
}
