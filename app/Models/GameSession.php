<?php
namespace App\Models;

use Carbon\Carbon;
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
     * @return mixed
     */
    public static function open($betId, $userId)
    {
        //проверяем есть ли такая ставка у этой игры
        $gameBet = GameBet::findOrFail($betId);
        /**
         * @var Game $game
         */
        $game = Game::findOrFail($gameBet->game_id);

        //проверяем есть уже сессия для этой игры
//        echo $betId . " \n";
//        echo $userId . " \n";
//        echo " \n";
        $session = self::whereNull('started_at')
            ->where('bet_id', $betId)
            ->get()
            ->first();

        if($session){
//            //проверяем нет ли этого пользователя уже в сессии
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
//                select COUNT(u.session_id) as count, `g`.`id`
//from `game_sessions` as `g`
//left join `game_sessions_users` as `u` on `u`.`session_id` = `g`.`id`
//where `g`.`started_at` is null
//                and `g`.`id` NOT IN (SELECT `game_sessions_users`.`session_id` FROM `game_sessions_users` WHERE `game_sessions_users`.`user_id` = 3)
//and `u`.`user_id` is not null
//group by `g`.`id` having COUNT(u.session_id) < 4


                 $session = self::select(DB::raw('
                    g.id,
                    COUNT(u.session_id) as count
                 '))
                    ->whereNull('g.started_at')
                    ->where('g.bet_id', $betId)
                    ->from('game_sessions as g')
                    ->leftJoin('game_sessions_users as u', function ($j) use ($userId){
                        $j->on('u.session_id', '=', 'g.id');
                    })
                    ->whereRaw('g.id NOT IN (SELECT `game_sessions_users`.`session_id` FROM `game_sessions_users` WHERE `game_sessions_users`.`user_id` = ' . $userId . ')')
                    ->groupBy('g.id')
                    ->havingRaw('count < ' . $game->need_users)
                    ->get()
                    ->first();

                 if($session){
                     GameSessionUser::create([
                         'user_id' => $userId,
                         'session_id' => $session->id
                     ]);

                     return $session->id;
                 }else{
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

    /**
     * @param $sessionId
     * @param $userId
     * @return bool
     */
    public static function close($sessionId, $userId)
    {
        /**
         * @var GameSession $session
         */
        $session = GameSession::where('session_id', $sessionId)
            ->first();

        if($session){
            $session->update([
                'winner_id' => $userId,
                'ended_at' => Carbon::now()
            ]);
            return true;
        }else{
            return false;
        }
    }
}
