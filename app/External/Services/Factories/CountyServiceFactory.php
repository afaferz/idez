<?php

namespace App\External\Services\Factories;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\External\Services\BrasilApiService;
use App\External\Services\IbgeService;
use Illuminate\Support\Facades\App;

class CountyServiceFactory
{
    public const SERVICES = [
        'brasil_api_service' => BrasilApiService::class,
        'ibge_api_service' => IbgeService::class,
    ];

    public static function create(string $serviceName): CountyServiceAbstract
    {
        $service = self::SERVICES[$serviceName] ?? null;

        if (is_null($service)) {
            throw new \Exception('The service provided is not valid');
        }

        return App::get($service);
    }
}