<?php

namespace App\External\Services;

// Refactor to enum
class CountyService
{
    public const AVAILABLES_SERVICES = [
        'brasil_api'=> IBGEService::class,
        'ibge_api'=> BrasilApiService::class,
    ];
}