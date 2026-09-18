<?php
namespace Pitan76\Todofile\Test;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Pitan76\Todofile\Command\CommandQuery;

class CommandQueryTest extends TestCase {
    #[TestDox('コマンド命令の文字列を正しく分割しとる')]
    public function testSplitQueryString(): void {
        $query = CommandQuery::parseString("phpunit --filter \"Foo Bar\"");

        $this->assertSame("phpunit", $query->getCmd());
        $this->assertSame("--filter \"Foo Bar\"", $query->getArgs());
    }

    #[TestDox('スペースの入ったコマンドのコマンド命令の文字列を正しく分割しとる')]
    public function testSplitQueryStringInSpace(): void {
        $query = CommandQuery::parseString("\"C:\\test dir\\app\" args1 args2");

        $this->assertSame("\"C:\\test dir\\app\"", $query->getCmd());
        $this->assertSame("args1 args2", $query->getArgs());
    }
}
