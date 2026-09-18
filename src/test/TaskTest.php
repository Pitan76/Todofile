<?php
namespace Pitan76\Todofile\Test;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Pitan76\Todofile\Command\CommandQuery;
use Pitan76\Todofile\Task\Task;

class TaskTest extends TestCase {
    #[TestDox('入れたコマンド命令の数とlengthが一致するとちゃいまっかー')]
    public function testLengthMatchesNumberOfCommands(): void {
        $queries = [
            CommandQuery::fromString("1+1"),
            CommandQuery::fromString("1+2"),
        ];

        $task = new Task("sum", $queries);
        $this->assertSame(count($queries), $task->length());
    }

    #[TestDox('次のコマンド命令がないときはnullを返すであろう')]
    public function testReturnsNullWhenNoNextCommandExists(): void {
        $queries = [
            CommandQuery::fromString("1+1"),
        ];

        $task = new Task("sum", $queries);

        $task->next();

        $this->assertnull($task->next());
    }

    #[TestDox('次のコマンドは一致するに決まっとる')]
    public function testNextReturnsFollowingCommand(): void {
        $queries = [
            CommandQuery::fromString("hoge"),
            CommandQuery::fromString("fuge"),
            CommandQuery::fromString("hage"),
        ];

        $task = new Task("test", $queries);

        $task->next();

        $this->assertSame("fuge", $task->next()->getString());
    }

    #[TestDox('空やったらそりゃnullやろ')]
    public function testReturnsNullWhenEmpty(): void {
        $queries = [];

        $task = new Task("empty", $queries);

        $this->assertNull($task->current());
    }
}
