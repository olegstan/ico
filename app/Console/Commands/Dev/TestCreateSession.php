<?php

namespace App\Console\Commands\Dev;

use App\Console\Commands\BaseCommand;
use App\Models\GameSession;

class TestCreateSession extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create:session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';


    /**
     * @throws \Exception
     */
    public function executeCommand()
    {
//        $session = GameSession::where();
    }

}
