<?php
namespace App\Models;

use App\Helpers\LoggerHelper;
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
        'win',
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
     * @throws \Exception
     * @throws \Throwable
     */
    public static function open($betId, $userId)
    {
        return DB::transaction(function () use ($betId, $userId){
            //проверяем есть ли такая ставка у этой игры
            $gameBet = GameBet::findOrFail($betId);
            /**
             * @var User $user
             */
            $user = User::findOrFail($userId);
            /**
             * @var Game $game
             */
            $game = Game::findOrFail($gameBet->game_id);

            //проверяем есть уже сессия для этой игры
//        echo $betId . " \n";
//        echo $userId . " \n";
//        echo " \n";
            $session = self::whereNull('started_at')
                ->whereNull('winner_id')
                ->where('bet_id', $betId)
                ->get()
                ->first();



            if($session){
//            //проверяем нет ли этого пользователя уже в сессии
                $session = self::select([
                        'g.id'
                    ])
                    ->from('game_sessions as g')
                    ->whereNull('g.winner_id')
                    ->whereNull('g.started_at')
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
                        ->whereNull('g.winner_id')
                        ->whereNull('g.started_at')
                        ->where('g.bet_id', $betId)
                        ->from('game_sessions as g')
                        ->leftJoin('game_sessions_users as u', function ($j){
                            $j->on('u.session_id', '=', 'g.id');
                        })
                        ->whereRaw('g.id NOT IN (SELECT `game_sessions_users`.`session_id` FROM `game_sessions_users` WHERE `game_sessions_users`.`user_id` = ' . $userId . ')')
                        ->groupBy('g.id')
//                        ->havingRaw('count < ' . $game->need_users)
                        ->get()
                        ->first();

                    if($session){
                        GameSessionUser::create([
                            'user_id' => $userId,
                            'session_id' => $session->id,
                            'credits_before' => $user->credits
                        ]);



                        return $session->id;
                    }else{
                        $session = GameSession::create([
                            'bet_id' => $gameBet->id
                        ]);

                        GameSessionUser::create([
                            'user_id' => $userId,
                            'session_id' => $session->id,
                            'credits_before' => $user->credits
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
                    'session_id' => $session->id,
                    'credits_before' => $user->credits
                ]);

                return $session->id;
            }
        });
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
        $session = GameSession::where('id', $sessionId)
            ->first();


        /**
         * @var GameBet $bet
         */
        $bet = GameBet::where('id', $session->bet_id)
            ->first();

        if($session)
        {
            $win  = $session->getWin();

            $session->update([
                'winner_id' => $userId,
                'ended_at' => Carbon::now(),
                'win' => $win
            ]);

            $gameSessions = GameSessionUser::where('session_id', $sessionId)
                ->get()
                ->all();

            foreach ($gameSessions as $gameSession)
            {
                $user = User::find($gameSession->user_id);
                if($gameSession->user_id == $userId)
                {
                    $user->increment('credits', $win);

                    $gameSession->update([
                        'credits_after' => $user->credits
                    ]);
                }else{
                    $userSess = User::where('id', $gameSession->user_id)
                        ->first();

                    $gameSession->update([
                        'credits_after' => $userSess->credits
                    ]);
                }
            }

            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $sessionId
     * @param $userId
     * @return bool
     */
    public static function exit($sessionId, $userId)
    {
        /**
         * @var GameSession $session
         */
        $session = GameSession::where('id', $sessionId)
            ->whereNull('started_at')
            ->whereNull('ended_at')
            ->first();

        if($session){
            $sessionUser = GameSessionUser::where('user_id', $userId)
                ->where('session_id', $sessionId)
                ->first();

            if($sessionUser){
                $sessionUser->delete();

                return true;
            }
        }
        return false;
    }

    /**
     * @param $sessionId
     * @return bool
     */
    public static function start($sessionId)
    {
        /**
         * @var GameSession $session
         */
        $session = GameSession::where('id', $sessionId)
            ->whereNull('started_at')
            ->first();

        if($session){
            $sessionUsers = GameSessionUser::where('session_id', $session->id)
                ->get()
                ->all();

            $gameBet = GameBet::findOrFail($session->bet_id);

            foreach ($sessionUsers as $sessionUser)
            {
                $user = User::where('id', $sessionUser->user_id)->first();
                if($user){
                    $user->decrement('credits', $gameBet->bet);
                }
            }

            $session->update([
                'started_at' => Carbon::now()
            ]);

            return true;
        }else{
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getWin()
    {
        try{
            $bet = GameBet::findOrFail($this->bet_id)->bet;

            $count = self::select(DB::raw('
                    COUNT(u.session_id) as count
                 '))
                ->from('game_sessions as g')
                ->where('g.id', $this->id)
                ->leftJoin('game_sessions_users as u', function ($j){
                    $j->on('u.session_id', '=', 'g.id');
                })
                ->groupBy('g.id')
                ->get()
                ->first()->count;

            return $bet * $count;
        }catch (\Exception $e){
            LoggerHelper::getLogger()->error($e);
            return 0;
        }
    }
}
