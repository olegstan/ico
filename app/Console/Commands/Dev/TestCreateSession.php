<?php

namespace App\Console\Commands\Dev;

use App\Console\Commands\BaseCommand;
use App\Models\Game;
use App\Models\GameBet;
use App\Models\GameSession;
use App\Models\User;

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
        $limit = 1000;
        for($i = 0; $i < $limit; $i++){
            $game = Game::inRandomOrder()
                ->first();

            $user = User::where('role_id', User::GAMER)
                ->inRandomOrder()
                ->first();

            $gameBet = GameBet::where('game_id', $game->id)
                ->inRandomOrder()
                ->first();

            if($user && $gameBet){
                GameSession::open($gameBet->id, 4);
            }
        }
    }

}
