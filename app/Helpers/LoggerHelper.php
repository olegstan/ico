<?php
namespace App\Helpers;

use Monolog\Logger;
use Log;

class LoggerHelper
{
    /**
     * @var array
     */
    public static $loggers = [];

    /**
     * @param Logger $logger
     * @param string $key
     */
    public static function rememberLogger($logger, $key = 'default')
    {
        self::$loggers[$key] = $logger;

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler){
            $logger->popHandler();
        }
    }

    /**
     * @param string $key
     * @return Logger
     */
    public static function getLogger($key = 'default')
    {
        if(!isset(self::$loggers[$key])){
            self::$loggers['default'] = Log::getLogger();
            return self::$loggers['default'];
        }
        return self::$loggers[$key];
    }
}