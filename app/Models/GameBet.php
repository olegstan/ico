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
}
