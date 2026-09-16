<?php
namespace Pitan76\Todofile\Exception;

use Exception;

/**
 * タスクファイルにタスクがまったく存在しない時の例外
 */
class TaskEmptyException extends Exception {

    public function __construct() {
        parent::__construct("Task is empty");
    }
}