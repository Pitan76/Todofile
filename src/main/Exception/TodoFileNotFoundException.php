<?php
namespace Pitan76\Todofile\Exception;

use Exception;

/**
 * タスクファイルが存在しない時の例外
 */
class TodoFileNotFoundException extends Exception {

    public function __construct() {
        parent::__construct("Task file is not found. Please create todofile.json5 or todofile.json.");
    }
}