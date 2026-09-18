<?php
namespace Pitan76\Todofile\Task;

use Pitan76\Todofile\Command\CommandQuery;
use Pitan76\Todofile\Exception\TaskNotFoundException;
use Pitan76\Todofile\TodofileLoader;

class TaskParser {

    public TodofileLoader $todofileLoader;

    public function __construct(TodofileLoader $todofileLoader) {
        $this->todofileLoader = $todofileLoader;
    }

    /**
     * タスクファイルを読み込んでタスクを作成する
     *
     * @param string $taskName タスク名
     * @return Task タスク
     * @throws TaskNotFoundException
     */
    public function parse(string $taskName): Task {
        // タスクファイルの読み込み
        $data = $this->todofileLoader->data;

        $commands = $data[$taskName] ?? null;

        // タスクが存在しない
        if ($commands === null) throw new TaskNotFoundException($taskName);

        // 文字列である場合は配列にする
        if (is_string($commands))
            $commands = [$commands];

        $queries = [];
        for ($i = 0; $i < count($commands); $i++) {
            $queries[$i] = CommandQuery::parseString($commands[$i]);
        }

        return new Task($taskName, $queries);
    }
}
