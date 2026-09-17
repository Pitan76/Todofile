<?php
namespace Pitan76\Todofile\Command;

use Pitan76\Todofile\Exception\CommandExecuteException;

class CommandExecutor {

    /**
     * @var array<int, false|resource> ファイルディスクリプタ
     */
    public const array DESCRIPTORS = [
        0 => STDIN,  // 標準入力
        1 => STDOUT, // 標準出力
        2 => STDERR  // 標準エラー
    ];

    /**
     * @return int 終了コード
     * @throws CommandExecuteException
     */
    public function execute(CommandQuery $query): int {
        // コマンドを実行する
        $process = proc_open($query, self::_getDescriptors(), $pipes);

        // todo: ここは続行するかどうかをtodo.jsonで決めれるようにすべき
        if (!is_resource($process)) throw new CommandExecuteException($query);

        // プロセスの終了を待ち、終了コードを取得
        return proc_close($process);
    }

    protected function _getDescriptors(): array {
        return self::DESCRIPTORS;
    }
}