<?php

namespace App\External\Services;

use App\External\Services\Abstractions\CountyServiceAbstract;

class BrasilApiService extends CountyServiceAbstract
{
    public const API_URI = 'https://brasilapi.com.br/api/';
    public const SEARCH_PATH = 'ibge/municipios/v1/%s';

    protected function getCounties(string $state): array
    {
        $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
        try {
            $response = $this->http::get($url);
        } catch (\Exception $exception) {
            $errorMessage = (sprintf('An Error Ocurred on Reques to %s for %s: %s', self::API_URI, $state, $exception->getMessage()));
            $this->handleLog($errorMessage);
            $this->handleLog($errorMessage);
        }
        return $this->parseJson($this->decodeJson($response));
    }

    protected function parseJson(array $response): array
    {
        $parsedResponse = [];

        for ($i = 0; $i < count($response); $i++) {
            $county = $response[$i];
            $parsedResponse[] = [
                'name' => $county['nome'],
                'ibge_code' => $county['codigo_ibge']
            ];
        }
        return $parsedResponse;
    }
}
