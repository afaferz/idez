<?php

namespace App\Providers;

use App\External\Services\BrasilApiService;
use App\External\Services\CountyService;
use App\External\Services\IBGEService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register service without use factory but using object template
        $this->app->bind(CountyService::class, function (): CountyService {
            $envService = env('COUNTY_API');
            $service = CountyService::AVAILABLES_SERVICES[$envService] ?? null;
            if (is_null($service)) {
                throw new \Exception('Provide a valiable API service');
            }

            return App::get($service);
        });

        $this->app->bind(BrasilApisService::class, function (): BrasilApiService {
            $httpClient = new Http();

            return new BrasilApiService($httpClient);
        });

        $this->app->bind(IbgeCountyService::class, function (): IBGEService {
            $httpClient = new Http();

            return new IBGEService($httpClient);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
