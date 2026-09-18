<?php
namespace Pitan76\Todofile\Exception;

use Exception;
use Pitan76\Todofile\Task\Task;

/**
 * タスクが存在しない時の例外
 */
class TaskNotFoundException extends Exception {

    /**
     * @param string|Task $task タスク または タスク名
     */
    public function __construct(string | Task $task) {
        $taskName = $task instanceof Task ? $task->getName() : $task;
        parent::__construct("Task '{$taskName}' was not found.");
    }
}