<?php
namespace Bolero\Forms\Commands;

use Bolero\Forms\CLI\Application;
use Bolero\Forms\Element;

abstract class AbstractCommand extends Element implements CommandInterface
{
    public function __construct(protected Application $application)
    {
        parent::__construct();
    }
}
