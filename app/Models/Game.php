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
        'logo',
        'need_users'
    ];

    /**
     * @return string
     */
    public function getLogo()
    {
        return '/games/' . $this->logo;
    }

    /**
     *
     */
    public function openSession()
    {

    }
}
