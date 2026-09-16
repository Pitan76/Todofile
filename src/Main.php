<?php
namespace Pitan76\Todofile;

use Exception;
use Pitan76\Todofile\Command\HelloCommand;
use Symfony\Component\Console\Application;

class Main {

    private Application $app;

    public function __construct() {
        $this->app = new Application('Todofile');
        $this->app->add(new HelloCommand());
    }

    /**
     * @throws Exception
     */
    public function run(): int {
        return $this->app->run();
    }
}