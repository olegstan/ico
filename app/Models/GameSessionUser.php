<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Game
 * @package App
 */
class GameSessionUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_sessions_users';
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'credits_before',
        'credits_after'
    ];
}
