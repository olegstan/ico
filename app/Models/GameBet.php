<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Game
 * @package App
 */
class GameBet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_bets';
    /**
     * @var array
     */
    protected $fillable = [
        'game_id',
        'bet'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function game()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    /**
     * @return string
     */
    public function openUrl()
    {
        return route('api.get.open.session')  . '?bet_id=' . $this->id;
    }
}
