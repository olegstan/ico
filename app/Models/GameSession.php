<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
/**
 * Class Game
 * @package App
 */
class GameSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_sessions';
    /**
     * @var array
     */
    protected $fillable = [
        'winner_id',
        'bet_id',
        'started_at',
        'ended_at'
    ];

    public function scopeNotPlayed()
    {

    }

    /**
     * @param $betId
     * @param $userId
     */
    public static function open($betId, $userId)
    {
        $userId = 4;
        //проверяем есть ли такая ставка у этой игры
        $gameBet = GameBet::findOrFail($betId);
        /**
         * @var Game $game
         */
        $game = Game::findOrFail($gameBet->game_id);

        //проверяем есть уже сессия для этой игры
        echo $betId . " \n";
        $session = self::whereNull('started_at')
            ->where('bet_id', $betId)
            ->get()
            ->first();

        if($session){
            //проверяем нет ли этого пользователя уже в сессии
            $session = self::whereNull('g.started_at')
                ->where('g.bet_id', $betId)
                ->where('u.user_id', $userId)
                ->from('game_sessions as g')
                ->leftJoin('game_sessions_users as u', function ($j){
                    $j->on('u.session_id', '=', 'g.id');
                })
                ->get()
                ->first();

            if($session){
                return $session->id;
            }else{
                 $session = self::whereNull('g.started_at')
                    ->where('g.bet_id', $betId)
                    ->from('game_sessions as g')
                    ->leftJoin('game_sessions_users as u', function ($j){
                        $j->on('u.session_id', '=', 'g.id');
                    })
                    ->groupBy('g.id')
                    ->havingRaw('COUNT(u.session_id) < ' . $game->need_users)
                    ->get()
                    ->first();

                 if($session){
                     echo 1 . " \n";
                     GameSessionUser::create([
                         'user_id' => $userId,
                         'session_id' => $session->id
                     ]);

                     return $session->id;
                 }else{
                     echo 2 . " \n";
                     $session = GameSession::create([
                         'bet_id' => $gameBet->id
                     ]);

                     GameSessionUser::create([
                         'user_id' => $userId,
                         'session_id' => $session->id
                     ]);

                     return $session->id;
                 }
            }
        }else{
            echo 3 . " \n";
            $session = GameSession::create([
                'bet_id' => $gameBet->id
            ]);

            GameSessionUser::create([
                'user_id' => $userId,
                'session_id' => $session->id
            ]);

            return $session->id;
        }
    }
}
