<?php

namespace App\External\Services;

use App\External\Service\GenericService;
use Illuminate\Support\Facades\Log;

class IBGEService extends GenericService
{
    public const API_URI = 'https://servicodados.ibge.gov.br/api/v1/';
    public const SEARCH_PATH = 'localidades/estados/%s/municipios';

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
                'county_code'=> $county['id']
            ];
        }
        return $parsedJson;
    }
}