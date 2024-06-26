<?php

namespace Bolero\Forms\CLI;

use Bolero\Forms\Commands\Constants\Lib;
use Bolero\Forms\Commands\ApplicationCommands;
use Bolero\Forms\Commands\CommandRunner;
use Bolero\Forms\Core\AbstractApplication;

class Application extends AbstractApplication
{
    protected array $argv = [];
    protected int $argc = 0;

    /**
     * Get the CLI argv array
     *
     * @return array
     */
    public function getArgv(): array
    {
        return $this->argv;
    }

    /**
     * Get the CLI argc value
     *
     * @return integer
     */
    public function getArgc(): int
    {
        return $this->argc;
    }

    /**
     * Get CLI argument by index
     *
     * @param integer $index
     * @return null|string NULL when the index is not in $argc range
     */
    public function getArgi(int $index, string $default = ''): null|string
    {
        if($index > -1 && $this->argc > $index) {
            return $this->argv[$index];
        }

        return $default;
    }

    public static function create(...$params): int
    {
        self::$instance = new Application();
        return self::$instance->run(...$params);
    }

    public function run(...$params): int
    {
        $this->argv = $params[0];
        $this->argc = $params[1];

        $this->appDirectory = APP_CWD;

        $this->loadInFile();

        self::setExecutionMode(Application::PROD_MODE);
        self::useTransactions(true);

        $this->init();

        return $this->execute();
    }

    public function init(): void
    {

    }

    protected function execute(): int
    {
        $commands = new ApplicationCommands($this);
        $runner = new CommandRunner($this, $commands);
        return $runner->run();
        
    }

    public function displayConstants(): array
    {
        try {
            $command = new Lib($this);
            $command->displayConstants();
        } catch (\Throwable $ex) {
            Console::error($ex);

            return [];
        }
    }

}
