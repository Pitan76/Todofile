<?php
namespace Pitan76\Todofile\Config;

use Pitan76\Todofile\TodofileLoader;

class Config {

    public array $config = [];

    public function __construct(TodofileLoader $todofileLoader) {
        if (!$todofileLoader->exists("@config")) return;
        $this->config = $todofileLoader->get("@config");
    }

    /**
     * @return bool コマンドの実行エラーを無視するか
     */
    public function ignoreCommandExecuteException(): bool {
        if (!$this->exists("ignoreCommandExecuteException")) return false;
        return self::get("ignoreCommandExecuteException");
    }

    public function exists(string $name): bool{
        return array_key_exists($name, $this->config);
    }

    public function get(string $key): mixed {
        return $this->config[$key] ?? null;
    }

}