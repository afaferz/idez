<?php

namespace App\External\Services;

use App\External\Service\GenericService;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\throwException;

class BrasilApiService extends GenericService
{
    private const API_URI = 'https://brasilapi.com.br/api/';
    private const SEARCH_PATH = 'ibge/municipios/v1/%s';


    protected function getInfo(string $state): array
    {   
        $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
        try {
            $response = $this->http::get($url);
        } catch(\Exception $error) {
            $errorMessage = sprintf("An Error Ocurred on Reques to %", self::API_URI) . "with state code {$state}". "ERROR: {$error->getMessage()}";
            Log::error($errorMessage);
        }
        return $this->decodeJson($response->json());
    }

    protected function parseJson(array $response): array
    {
        $parsedJson = [];

        for($i = 0; $i < count($response); $i++) {
            $county = $response[$i];
            $parsedJson[]= [
                'county_name'=> $county['name'],
                'county_code'=> $county['codigo_ibge']
            ];
        }
        return $parsedJson;
    }
}