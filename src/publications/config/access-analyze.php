<?php

return [
    /**
     * Basic
     * 
     * 基本設定
     * 
     * enable       : bool アクセス解析をログに残すかどうか
     * log_directory: string アクセス解析ログの出力先ディレクトリ
     * 
     */
    "enable"        => env("ACCESS_ANALYZE_ENABLE", false),
    "log_directory" => env("ACCESS_ANALYZE_LOG_DIRECTORY", "access"),


    /**
     * Logging
     * 
     * 各項目をログに出力するかどうか
     * 
     * request_url        : bool アクセスURL
     * request_http_method: bool アクセスHTTPメソッド
     * request_user_agent : bool アクセスしたユーザのUser Agent
     * request_ip_address : bool アクセスした端末のIP Address
     * 
     * response_status : bool レスポンスのステータスコードとテキスト
     * 
     * execution_time   : bool リクエストからレスポンスまでの実行時間
     * memory_peak_usage: bool リクエストからレスポンスまでの最大メモリ使用量
     */
    "request_url"         => env("ACCESS_ANALYZE_REQUEST_URL", false),
    "request_http_method" => env("ACCESS_ANALYZE_REQUEST_HTTP_METHOD", false),
    "request_user_agent"  => env("ACCESS_ANALYZE_REQUEST_USER_AGENT", false),
    "request_ip_address"  => env("ACCESS_ANALYZE_REQUEST_IP_ADDRESS", false),

    "response_status" => env("ACCESS_ANALYZE_RESPONSE_STATUS", false),

    "execution_time"    => env("ACCESS_ANALYZE_EXECUTION_TIME", false),
    "memory_peak_usage" => env("ACCESS_ANALYZE_MEMORY_PEAK_USAGE", false),


    /**
     * Ignore URL
     * 
     * ログを残さないURL
     */
    "ignore_url" => [],
];
