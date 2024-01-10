<?php

use SimpleLogger\Laravel\Logger;
use SimpleLogger\Laravel\Facade\Logger as LoggerFacade;

if (!function_exists("isEnableAccessAnalyze")) {

    /**
     * Access Analyzeでログを残すかどうか
     * 
     * @return bool
     */
    function isEnableAccessAnalyze(): bool
    {
        return config("access-analyze.enable", false);
    }
}

if (!function_exists("getAccessLogger")) {

    /**
     * Access Analyzeで使用するLoggerを取得する
     * 
     * @return Logger
     */
    function getAccessLogger(): Logger
    {
        $logger = LoggerFacade::info();

        $logger->setDirectory(config("access-analyze.log_directory", "access"));

        return $logger;
    }
}

if (!function_exists("accessLog")) {

    /**
     * Access Analyzeのログに追加で出力する
     * 
     * @param mixed $message
     * @param mixed $value
     * @return void
     */
    function accessLog(mixed $message, mixed $value = null): void
    {
        if (!isEnableAccessAnalyze()) return;

        $logger = getAccessLogger();

        $logger->add($message, $value);

        $logger->logging();
    }
}
