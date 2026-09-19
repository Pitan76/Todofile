<?php

namespace Pitan76\Todofile\Exception;

use Exception;

/**
 * サポートしていない拡張子エラー
 */
class UnsupportedExtensionException extends Exception {

    /**
     * @param string $ext 拡張子名
     */
    public function __construct(string $ext) {
        parent::__construct("The file extension '{$ext}' is not supported.");
    }
}