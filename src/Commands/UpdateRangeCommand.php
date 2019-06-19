<?php
namespace Xiaohuilam\LaravelAliyunWafTrustedproxy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Xiaohuilam\LaravelAliyunWafTrustedproxy\Traits\HasCacheKey;

class UpdateRangeCommand extends Command
{
    use HasCacheKey;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waf:update-range';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '阿里云WAF更新回源IP段';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $content = file_get_contents('https://api.github.com/repos/xiaohuilam/laravel-aliyun-waf-trustedproxy/issues/1', false, stream_context_create([
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36\r\n"
            ],
        ]));
        $json = json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE || !$content || !$json || !isset($json->body)) {
            return $this->error('更新IP段时，网络错误！');
        }

        $text = $json->body;
        $ranges = explode(',', $text);
        Cache::put($this->cache_key, $ranges);

        $this->info('更新IP段成功！' . $text);
    }
}
