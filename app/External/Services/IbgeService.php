<?php

namespace App\External\Services;

use App\External\Services\Abstractions\CountyServiceAbstract;

class IbgeService extends CountyServiceAbstract
{
    public const API_URI = 'https://servicodados.ibge.gov.br/api/v1/';
    public const SEARCH_PATH = 'localidades/estados/%s/municipios';
    // Could not request to IBGE Api because OpenSSL and TLS version is older
    private const INSECURE_SSL_ERROR = 'Could not request to %s for %s due insecure TLS and SSL version.';

    protected function getCounties(string $state): array
    {
        $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
        try {
            // Disable SSL verify because TLS and OpenSSL from IBGE is old
            $response = $this->http::withoutVerifying()->withOptions(["verify" => false])->get($url);
        } catch (\Exception $exception) {
            $errorMessage = (sprintf(self::INSECURE_SSL_ERROR, self::API_URI, $state, $exception->getMessage()));
            $this->handleLog($errorMessage);
            $this->handleError($errorMessage);
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
                'ibge_code' => $county['id']
            ];
        }
        return $parsedResponse;
    }
}
