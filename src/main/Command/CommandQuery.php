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
    public static function parseString(string $str): CommandQuery {
        $parts = self::parseAsParts($str);

        return new CommandQuery($parts[0], $parts[1] ?? "");
    }

    /**
     * @param string $str 文字列
     * @return array<string, string> パーツ
     */
    private static function parseAsParts(string $str): array {
        $length = strlen($str);
        $quote = null;

        for ($i = 0; $i < $length; $i++) {
            $char = $str[$i];

            if ($char === '"' || $char === "'") {
                if ($quote === null) {
                    $quote = $char;
                    continue;
                }

                if ($quote === $char)
                    $quote = null;

                continue;
            }

            // クォート外の最初の空白
            if (ctype_space($char) && $quote === null) {
                return [
                    substr($str, 0, $i),
                    ltrim(substr($str, $i)),
                ];
            }
        }

        return [$str, ''];
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
