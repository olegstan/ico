<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'configs';

    /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['created_at', 'updated_at'];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
		'name',
		'key',
	];

    /**
     * @param $key
     * @return mixed
     */
	public static function getValue($key)
    {
	    if($config = self::where('key', $key)->get()->first())
        {
            return $config->value;
        }else{
	        LoggerHelper::getLogger()->error('Not found config with key: ' . $key);
        }
	}

    /**
     * @param $key
     * @param $value
     */
	public static function setValue($key, $value)
    {
        if($config = self::where('key', $key)->get()->first()){
            $config->update([
                'value' => $value
            ]);
        }else{
            self::create([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
	
	public static function getSecondsCancelOrderTime(){
		return intval(self::getValue('time_available_cancel_order')) * 60 * 60;
	}
}
