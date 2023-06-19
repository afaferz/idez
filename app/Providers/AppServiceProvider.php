<?php

namespace App\Providers;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\External\Services\Factories\CountyServiceFactory;
use App\External\Services\BrasilApiService;
use App\External\Services\IbgeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CountyServiceAbstract::class, function (): CountyServiceAbstract {
            $service = env('SERVICE_COUNTY');
            return CountyServiceFactory::create($service);
        });

        $this->app->bind(BrasilApiService::class, function (): BrasilApiService {
            $httpClient = new Http();

            return new BrasilApiService($httpClient);
        });

        $this->app->bind(IbgeService::class, function (): IbgeService {
            $httpClient = new Http();

            return new IbgeService($httpClient);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // \Illuminate\Support\Facades\URL::forceScheme('https');
        // if($this->app->environment('production')) {
        // }
    }
}
