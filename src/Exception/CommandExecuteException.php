<?php

namespace Pitan76\Todofile\Exception;

use Exception;

/**
 * コマンド実行エラー
 */
class CommandExecuteException extends Exception {

    public function __construct($query) {
        parent::__construct("Command execute failed: {$query}");
    }
}