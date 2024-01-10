<?php

namespace LaravelAccessAnalyze;

use Illuminate\Support\ServiceProvider as Provider;

use Illuminate\Contracts\Http\Kernel;
use LaravelAccessAnalyze\Middleware;

/**
 * ServiceProvider
 * Middlewareの登録とパッケージに含まれるファイルの公開の設定を行う
 * 
 * @package LaravelAccessAnalyze
 */
class ServiceProvider extends Provider
{
    /**
     * publications配下を公開する際に使うルートパス
     *
     * @var string
     */
    private string $publicationsPath = __DIR__ . DIRECTORY_SEPARATOR . "publications";


    /**
     * アプリケーションの起動時に実行する
     * Middlewareのインスタンスをシングルトンとして登録する
     * 
     * @return void
     */
    public function register(): void
    {
        // Middlewareのインスタンスをシングルトンとして登録する
        $this->app->singleton(Middleware::class);
    }

    /**
     * アプリケーションのブート時に実行する
     * Middlewareを登録する
     * パッケージに含まれるファイルの公開の設定を行う
     * 
     * @return void
     */
    public function boot(): void
    {
        // Middlewareの登録
        $kernel = app(Kernel::class);
        $kernel->pushMiddleware(Middleware::class);

        // config配下の公開
        // 自作パッケージ共通タグ
        $this->publishes([
            $this->publicationsPath . DIRECTORY_SEPARATOR . "config" => config_path(),
        ], "publications");

        // このパッケージのみ
        $this->publishes([
            $this->publicationsPath . DIRECTORY_SEPARATOR . "config" => config_path(),
        ], "access-analyze");
    }
}
