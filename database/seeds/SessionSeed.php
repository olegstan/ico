<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Game;
use App\Models\GameBet;
use App\Models\GameSession;

class SessionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game = Game::inRandomOrder()->first();
        $limit = 30;
        for($i = 0; $i < $limit; $i++){
            $user = User::where('role_id', User::GAMER)
                ->inRandomOrder()
                ->first();

            $gameBet = GameBet::where('game_id', $game->id)
                ->inRandomOrder()
                ->first();

            if($user && $gameBet){
                GameSession::open($gameBet->id, $user->id);
            }
        }
    }
}
