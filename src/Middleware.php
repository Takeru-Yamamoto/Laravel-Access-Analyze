<?php

namespace LaravelAccessAnalyze;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

use SimpleTimer\Laravel\Timer;
use SimpleTimer\Laravel\Facade\Timer as TimerFacade;

/**
 * applicationに対するすべてのリクエストをログに残すmiddleware
 * 
 * enableでログを残すかを決定する
 * log_directoryでログを出力するディレクトリを指定する
 * ignore_urlに指定されたURLに対するリクエストはログに残さない
 * 
 * @package LaravelAccessAnalyze
 */
class Middleware
{
    /**
     * ログを残すかどうか
     *
     * @var bool
     */
    public bool $isEnable;

    /**
     * 処理の実行時間を計測するためのTimer
     *
     * @var Timer
     */
    public Timer $timer;


    /**
     * コンストラクタ
     * 
     * @return void
     */
    function __construct()
    {
        $this->isEnable = isEnableAccessAnalyze();

        $ignoreUrl = config("access-analyze.ignore_url", []);

        foreach ($ignoreUrl as $url) {
            if (str_contains(request()->getRequestUri(), $url)) $this->isEnable = false;
        }
    }

    /**
     * applicationに対するすべてのリクエストをログに残すmiddleware
     * 
     * configの各項目のboolを用いて、どの項目に関するログを残すかを決定する
     *
     * @param Request $request
     * @param \Closure $next
     */
    public function handle(Request $request, \Closure $next): SymfonyResponse
    {
        if (!$this->isEnable) return $next($request);

        $logger = getAccessLogger();

        $logger->addDivider();
        $logger->add("ACCESS ANALYZE");
        $logger->addEmpty();

        $logger->logging();

        $this->timer = TimerFacade::start();

        return $next($request);
    }

    /**
     * applicationに対するすべてのリクエストが終了した後に呼び出される
     * 
     * configの各項目のboolを用いて、どの項目に関するログを残すかを決定する
     *
     * @param Request $request
     * @param IlluminateResponse|RedirectResponse|JsonResponse $response
     * @return void
     */
    public function terminate(Request $request, IlluminateResponse|RedirectResponse|JsonResponse $response): void
    {
        if (!$this->isEnable) return;

        $logger = getAccessLogger();

        $this->timer->stop();

        $logger->addEmpty();

        if (config("access-analyze.request_url")) $logger->add("Request URL", e($request->getRequestUri()));
        if (config("access-analyze.request_http_method")) $logger->add("Request HTTP Method", $request->method());
        if (config("access-analyze.request_user_agent")) $logger->add("Request User Agent", $request->userAgent());
        if (config("access-analyze.request_ip_address")) $logger->add("Request IP Address", $request->ip());

        if (config("access-analyze.response_status")) $logger->add("Response Status", $response->status() . ": " . $response->statusText());

        if (config("access-analyze.execution_time")) $logger->add("Execution Time", $this->timer->elapsedMilliseconds() . " ms");
        if (config("access-analyze.memory_peak_usage")) $logger->add("Memory Peak Usage", (memory_get_peak_usage() / (1024 * 1024)) . " MB (" . memory_get_peak_usage() . " byte)");

        $logger->addDivider();

        $logger->logging();
    }
}
