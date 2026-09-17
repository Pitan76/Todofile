<?php
namespace Pitan76\Todofile\Command;

use Pitan76\Todofile\Main;
use Symfony\Component\Console\Command\Command;

class Commands {
    /**
     * コマンドを追加する
     */
    public static function init(): void {
        self::register(new HelloCommand());
    }

    /**
     * コマンドを登録する
     *
     * @param Command $command コマンド
     */
    public static function register(Command $command): void {
        Main::$INSTANCE->app->add($command);
    }
}
