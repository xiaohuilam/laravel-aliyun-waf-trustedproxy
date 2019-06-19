<?php
namespace Xiaohuilam\LaravelAliyunWafTrustedproxy;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as Artisan;
use Xiaohuilam\LaravelAliyunWafTrustedproxy\Traits\HasCacheKey;
use Xiaohuilam\LaravelAliyunWafTrustedproxy\Commands\UpdateRangeCommand;

class LaravelAliyunWafTrustedproxyServiceProvider extends ServiceProvider
{
    use HasCacheKey;

    public function boot()
    {
        $proxies = collect(config()->get('trustedproxy.proxies', []));
        config()->set('trustedproxy.proxies', $proxies->merge($this->loadDefaultRanges())->toArray());
    }

    public function register()
    {
        if (!method_exists(app(), 'runningInConsole') || app()->runningInConsole()) {
            $this->registerCommand();
        }
    }

    protected function registerCommand()
    {
        $command = UpdateRangeCommand::class;

        Artisan::starting(function ($artisan) use ($command) {
            $artisan->resolve($command);
        });
    }

    protected function loadDefaultRanges()
    {
        return Cache::get($this->cache_key, function () {
            $this->mergeConfigFrom(__DIR__ . './../config/range.php', $this->cache_key);
            $ranges = config($this->cache_key);
            Cache::put($this->cache_key, $ranges);

            return $ranges;
        });
    }
}
