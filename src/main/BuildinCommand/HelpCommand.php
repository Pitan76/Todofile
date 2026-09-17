<?php
namespace Pitan76\Todofile\BuildinCommand;

use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelpCommand extends Command {

    #[Override]
    protected static $defaultName = '@help';

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $output->writeln('Hello, TodoFile!');
        return Command::SUCCESS;
    }
}
