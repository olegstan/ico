<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Events\QueryExecuted;
use Monolog\Logger;
use Auth;
use App;
use File;
use DB;
use Log;
use App\Models\User;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use App\Helpers\LoggerHelper;

abstract class BaseCommand extends Command
{
	/**
	 * BaseCommand constructor.
	 */
    public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 */
	public function handle()
	{
	    if(env('EXTENDED_LOG', false)){
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

            $path = storage_path('logs/commands/' . $this->prepareKey($this->signature));
            if(!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            $filename = $path . '/laravel.log';
            $this->setHandler($monolog, $filename);
            $filename = $path . '/query.log';
            $this->setHandler($monologQuery, $filename);
            $filename = $path . '/validation.log';
            $this->setHandler($monologValidation, $filename);
            $filename = $path . '/helper.log';
            $this->setHandler($monologHelper, $filename);

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
        }

		Log::info('Выполнение команды ' . $this->prepareKey($this->signature) . '  начато.');
		$this->executeCommand();
		Log::info('Выполнение команды ' . $this->prepareKey($this->signature) . ' завершено.');
	}


	/**
	 * @return Logger
	 */
	public function removeHandlers($monolog)
	{
		$handlers = $monolog->getHandlers();
		foreach ($handlers as $handler) {
			$monolog->popHandler();
		}
		return $monolog;
	}
	
	public function executeCommand()
	{
		
	}

	public function authLikeBot()
	{
		$bot = User::where('name', 'bot')->get()->first();
		Auth::loginUsingId($bot->id);
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
}
