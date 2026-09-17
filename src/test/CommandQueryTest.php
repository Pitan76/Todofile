<?php
namespace Pitan76\Todofile\Test;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Pitan76\Todofile\Command\CommandQuery;

class CommandQueryTest extends TestCase {
    #[TestDox('コマンド命令の文字列を正しく分割しとる')]
    public function testSplitQueryString(): void {
        $query = CommandQuery::fromString("phpunit --filter \"Foo Bar\"");

        $this->assertSame("phpunit", $query->getCmd());
        $this->assertSame("--filter \"Foo Bar\"", $query->getArgs());
    }
}
