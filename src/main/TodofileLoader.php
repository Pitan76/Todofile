<?php
namespace Pitan76\Todofile;

class TodofileLoader {
    public array $data = [];

    public function __construct(string $filename) {
        // タスクファイルの読み込み
        $this->data = json_decode(file_get_contents($filename), true);
    }

    public function exists(string $key): bool {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key): mixed {
        return $this->data[$key] ?? null;
    }
}