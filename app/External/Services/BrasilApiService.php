<?php

namespace App\External\Services;

use App\External\Services\Abstractions\CountyServiceAbstract;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class BrasilApiService extends CountyServiceAbstract
{
    public const API_URI = 'https://brasilapi.com.br/api/';
    public const SEARCH_PATH = 'ibge/municipios/v1/%s/';

    protected function getCounties(string $state): array
    {
        try {
            $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
            $response = $this->httpClient::get($url);
            $response->throw();
        } catch (RequestException $e) {
            $this->handleLog($e->getMessage());
            $this->handleError($this->genGenericErrorMessage($state));
        } catch (\Exception $e) {
            $this->handleLog($e->getMessage());
            $this->handleError($this->genGenericErrorMessage($state));
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
    private function genGenericErrorMessage(string $state)
    {
        return sprintf('An Error Ocurred on Reques to %s for %s', self::API_URI, $state);
    }

}
