<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;
use File;
use Log;
use App;
use DB;
use App\Helpers\LoggerHelper;
use Request;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class LogProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(!App::runningInConsole() && env('EXTENDED_LOG', false)){

            $monolog = Log::getLogger();
            LoggerHelper::rememberLogger($monolog);
            /**
             * @var Logger $monologQuery
             */
            $monologQuery = new Logger('query');
            LoggerHelper::rememberLogger($monologQuery, 'query');
            /**
             * @var Logger $monologValidation
             */
            $monologValidation = new Logger('validation');
            LoggerHelper::rememberLogger($monologValidation, 'validation');
            /**
             * @var Logger $monologValidation
             */
            $monologHelper = new Logger('helper');
            LoggerHelper::rememberLogger($monologHelper, 'helper');
            /**
             * @var Logger $monologSystem
             */
            $monologSystem = new Logger('system');
            LoggerHelper::rememberLogger($monologSystem, 'system');

            $path = storage_path('logs/');
            if(!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            if(function_exists('posix_geteuid')){
                $processUser = posix_getpwuid( posix_geteuid() );
                $processName= $processUser[ 'name' ];

                $filename = $path . '/laravel-' . php_sapi_name() . '-' . $processName . '.log';
                $this->setHandler($monolog, $filename);
                $filename = $path . '/query-' . php_sapi_name() . '-' . $processName . '.log';
                $this->setHandler($monologQuery, $filename);
                $filename = $path . '/validation-' . php_sapi_name() . '-' . $processName . '.log';
                $this->setHandler($monologValidation, $filename);
                $filename = $path . '/helper-' . php_sapi_name() . '-' . $processName . '.log';
                $this->setHandler($monologHelper, $filename);
                $filename = $path . '/system-' . php_sapi_name() . '-' . $processName . '.log';
                $this->setHandler($monologSystem, $filename);
            }else{
                $filename = $path . '/laravel.log';
                $this->setHandler($monolog, $filename);
                $filename = $path . '/query.log';
                $this->setHandler($monologQuery, $filename);
                $filename = $path . '/validation.log';
                $this->setHandler($monologValidation, $filename);
                $filename = $path . '/helper.log';
                $this->setHandler($monologHelper, $filename);
                $filename = $path . '/system.log';
                $this->setHandler($monologSystem, $filename);
            }


//        if(env('APP_ENV') === 'local'){
            DB::listen(function($sql)
            {
                /**
                 * @var QueryExecuted $sql
                 */
                LoggerHelper::getLogger('query')->debug(
                    'SQL => ' . $sql->sql . "\n" .
                    'TIME => ' . $sql->time . ' milliseconds' . "\n" .
                    'BIND => ' . print_r($sql->bindings, true)
                );
            });
//        }
        }
    }

    /**
     * @param Logger $monolog
     * @param $filename
     */
    public function setHandler($monolog, $filename)
    {
        $handler = new RotatingFileHandler($filename, 10, Logger::DEBUG, true, 0777);
        $handler->setFormatter(new LineFormatter(null, 'Y-m-d H:i:s', true, true));
        $monolog->pushHandler($handler);
    }

    /**
     * @param $key
     * @return null|string|string[]
     */
    public function prepareKey($key)
    {
        $key = str_replace(' ', '-', $key);

        return preg_replace('/[^A-Za-z0-9\-]/', '', $key);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
