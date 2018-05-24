<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Game
 *
 * @property string $name
 * @property integer $need_users
 *
 * @package App
 */
class Game extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'games';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'need_users'
    ];

    /**
     *
     */
    public function openSession()
    {

    }
}
