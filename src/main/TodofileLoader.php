<?php
namespace Pitan76\Todofile;

use ColinODell\Json5\Json5Decoder;
use ColinODell\Json5\SyntaxError;
use Pitan76\Todofile\Exception\TodoFileNotFoundException;

class TodofileLoader {

    // タスクファイル名
    public const string FILENAME = "todo.json";
    public const string FILENAME_JSON5 = "todo.json5"; // コメントできる版

    public array $data = [];

    public function __construct() {
        $this->loadJson();
    }

    /**
     * @throws SyntaxError
     */
    public function loadJson(): void
    {
        // タスクファイルの読み込み
        if (file_exists(self::FILENAME_JSON5)) {
            $this->data = Json5Decoder::decode(file_get_contents(self::FILENAME_JSON5), true);
            return;
        }

        if (file_exists(self::FILENAME)) {
            $this->data = json_decode(file_get_contents(self::FILENAME), true);
            return;
        }

        throw new TodoFileNotFoundException();
    }

    public function exists(string $key): bool {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key): mixed {
        return $this->data[$key] ?? null;
    }
}