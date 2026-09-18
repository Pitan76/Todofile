<?php
namespace Pitan76\Todofile\Test;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Pitan76\Todofile\Command\CommandExecutor;
use Pitan76\Todofile\Command\CommandQuery;
use Pitan76\Todofile\Main;
use Pitan76\Todofile\Task\Task;

class MainTest extends TestCase {

    #[TestDox("タスク内のコマンドをすべてCommandExecutorに渡す")]
    public function testExecutesAllCommandsInTask(): void {
        $queries = [
            CommandQuery::fromString("hoge"),
            CommandQuery::fromString("fuge"),
            CommandQuery::fromString("piyo"),
        ];

        $count = count($queries);

        $task = new Task("test", $queries);

        $mock = $this->createMock(CommandExecutor::class);
        $mock->expects($this->exactly($count))
            ->method('execute')
            ->willReturn(0);

        $main = new Main();
        $main->executor = $mock;

        $main->runTask($task);
    }
}
