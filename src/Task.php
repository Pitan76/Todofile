<?php
namespace Pitan76\Todofile;

class Task {
    /**
     * タスク名
     */
    public string $name;

    /**
     * @var array<string> コマンドの配列
     */
    public array $commands;

    /**
     * @var int 現在の実行コマンド行
     */
    private int $index = 0;

    /**
     * @param string $name タスク名
     * @param array $commands コマンドの配列
     */
    public function __construct(string $name, array $commands = []) {
        $this->name = $name;
        $this->commands = $commands;
    }

    /**
     * @return string タスク名
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string|null 現在のコマンドを返してから次へ進む
     */
    public function next(): ?string {
        if ($this->index >= count($this->commands))
            return null;

        return $this->commands[$this->index++];
    }

    /**
     * @return string|null 現在のコマンド
     */
    public function current(): ?string {
        return $this->commands[$this->index] ?? null;
    }

    /**
     * @return int コマンド数
     */
    public function length(): int {
        return count($this->commands);
    }

    /**
     * @return int 現在の行
     */
    public function getIndex(): int {
        return $this->index;
    }

    /**
     * @param string $command コマンド
     */
    public function addCommand(string $command): void {
        $this->commands[] = $command;
    }

    /**
     * @param array<string> $commands コマンドの配列
     */
    public function addCommands(array $commands): void {
        $this->commands = array_merge($this->commands, $commands);
    }

    public function __toString(): string {
        return $this->name;
    }
}