<?php
namespace Pitan76\Todofile\Command;

/**
 * コマンド命令
 */
class CommandQuery {
    /**
     * @var string コマンド
     */
    public string $cmd = "";

    /**
     * @todo array<string> にするかもしれないがとりあえず最低限としてstringにする
     * @var string 引数
     */
    public string $args = "";

    /**
     * @param string $cmd コマンド
     * @param string $args 引数
     */
    public function __construct(string $cmd, string $args = "") {
        $this->cmd = $cmd;
        $this->args = $args;
    }

    /**
     * @param string $str コマンド命令の文字列
     * @return CommandQuery コマンド命令
     */
    public static function fromString(string $str): CommandQuery {
        $parts = preg_split('/\s+/', $str, 2);

        return new CommandQuery($parts[0], $parts[1] ?? "");
    }

    /**
     * @return string コマンド
     */
    public function getCmd(): string {
        return $this->cmd;
    }

    /**
     * @return string 引数
     */
    public function getArgs(): string {
        return $this->args;
    }

    /**
     * @return string コマンド命令の文字列
     */
    public function getString(): string {
        return ($this->args !== "")
            ? $this->cmd . ' ' . $this->args : $this->cmd;
    }

    /**
     * @return string コマンド命令の文字列
     */
    public function __toString(): string {
        return $this->getString();
    }
}
