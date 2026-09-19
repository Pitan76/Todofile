<?php
namespace Pitan76\Todofile;

use ColinODell\Json5\Json5Decoder;
use ColinODell\Json5\SyntaxError;
use Pitan76\Todofile\Exception\TodoFileNotFoundException;
use Pitan76\Todofile\Exception\UnsupportedExtensionException;

class TodofileLoader {

    // タスクファイル名
    public const string FILENAME = "todofile.json";
    public const string FILENAME_JSON5 = "todofile.json5"; // コメントできる版

    public array $data = [];

    public ?string $filename = null;

    /**
     * @throws SyntaxError | TodoFileNotFoundException | UnsupportedExtensionException
    */
    public function __construct(?string $filename = null) {
        $this->filename = $filename;
        $this->loadJson();
    }

    /**
     * @throws SyntaxError | TodoFileNotFoundException | UnsupportedExtensionException
     */
    public function loadJson(): void {
        $ext = "json5";

        if ($this->filename !== null) {
            if (!file_exists($this->filename)) throw new TodoFileNotFoundException($this->filename);
            $ext = pathinfo($this->filename, PATHINFO_EXTENSION);
        } else if (file_exists(self::FILENAME_JSON5)) {
            $this->filename = self::FILENAME_JSON5;
        } else if (file_exists(self::FILENAME)) {
            $this->filename = self::FILENAME;
            $ext = "json";
        } else {
            throw new TodoFileNotFoundException();
        }

        // タスクファイルの読み込み
        if ($ext === "json5") {
            $this->data = Json5Decoder::decode(file_get_contents($this->filename), true);
            return;
        }

        if ($ext === "json") {
            $this->data = json_decode(file_get_contents($this->filename), true);
            return;
        }

        throw new UnsupportedExtensionException($ext);
    }

    public function exists(string $key): bool {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key): mixed {
        return $this->data[$key] ?? null;
    }
}