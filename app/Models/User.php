<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Hash;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'token',
        'credits',
        'password',
        'remember_token',
        'role_id'
    ];

    protected $hidden = ['password', 'remember_token'];
    
    
    CONST ADMIN = 1;
    CONST GAMER = 2;


    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setRoleIdAttribute($input)
    {
        $this->attributes['role_id'] = $input ? $input : null;
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

    /**
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $model = new static($attributes);

        $model->token = self::getUniqueHash();

        $model->save();

        return $model;
    }

    /**
     * @return string
     */
    public static function getUniqueHash()
    {
        $hash = static::getUniqueString(20);
        if(User::where('token', '=', $hash)->get()->first()){
            return self::getUniqueHash();
        }else{
            return $hash;
        }
    }

    /**
     * @param $length
     * @return string
     */
    public static function getUniqueString($length)
    {
        return str_random($length);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $role
     * @return bool
     */
    public function is($role)
    {
        $role = strtolower($role);

        switch ($role){
            case 'admin':
                return $this->role_id == self::ADMIN;
            case 'gamer':
                return $this->role_id == self::GAMER;
            default:
                return false;
        }
    }

    /**
     * @return string
     */
    public function getSlugRole()
    {
        switch ($this->role_id){
            case self::ADMIN:
                return 'admin';
            case self::GAMER:
                return 'gamer';
            default:
                return '';
        }
    }
}
