<?php
namespace Pitan76\Todofile;

class Task {
    /**
     * タスク名
     */
    public string $name;

    /**
     * @var array<CommandQuery> コマンド命令の配列
     */
    public array $commands;

    /**
     * @var int 現在の実行している行
     */
    private int $index = 0;

    /**
     * @param string $name タスク名
     * @param array<CommandQuery> $queries コマンド命令の配列
     */
    public function __construct(string $name, array $queries = []) {
        $this->name = $name;
        $this->commands = $queries;
    }

    /**
     * @return string タスク名
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return CommandQuery|null 現在のコマンド命令を返してから次へ進む
     */
    public function next(): ?CommandQuery {
        if ($this->index >= count($this->commands))
            return null;

        return $this->commands[$this->index++];
    }

    /**
     * @return CommandQuery|null 現在のコマンド命令
     */
    public function current(): ?CommandQuery {
        return $this->commands[$this->index] ?? null;
    }

    /**
     * @return int 行数
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
     * @param CommandQuery $command コマンド命令
     */
    public function addCommand(CommandQuery $command): void {
        $this->commands[] = $command;
    }

    /**
     * @param array<CommandQuery> $commands コマンド命令の配列
     */
    public function addCommands(array $commands): void {
        $this->commands = array_merge($this->commands, $commands);
    }

    public function __toString(): string {
        return $this->getName();
    }
}