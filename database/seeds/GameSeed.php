<?php

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\GameBet;

class GameSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = 30;
        for($i = 0; $i < $limit; $i++){
            $game = Game::create([
                'name' => 'game-'.$i,
                'need_users' => rand(2, 4)
            ]);

            $limitInner = rand(2, 6);
            for($k = 0; $k < $limitInner; $k++){
                $bet = rand(2, 15);
                $gameBet = GameBet::where('game_id', $game->id)
                    ->where('bet', $bet)
                    ->first();

                if(!$gameBet){
                    GameBet::create([
                        'game_id' => $game->id,
                        'bet' => $bet
                    ]);
                }
            }
        }
    }
}
