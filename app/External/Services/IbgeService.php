<?php

namespace App\External\Services;

use App\External\Services\Abstractions\CountyServiceAbstract;
use Illuminate\Http\Client\RequestException;

class IbgeService extends CountyServiceAbstract
{
    public const API_URI = 'https://servicodados.ibge.gov.br/api/v1/';
    public const SEARCH_PATH = 'localidades/estados/%s/municipios';
    // Could not request to IBGE Api because OpenSSL and TLS version is older
    private const INSECURE_TLS_SSL_ERROR = 'Could not request due insecure TLS and SSL version.';

    protected function getCounties(string $state): array
    {
        $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
        try {
            $url = self::API_URI . sprintf(self::SEARCH_PATH, $state);
            $response = $this->httpClient::withoutVerifying()->withOptions(["verify" => false])->get($url);
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
                'ibge_code' => $county['id']
            ];
        }
        return $parsedResponse;
    }
    private function genGenericErrorMessage(string $state)
    {
        return sprintf('An Error Ocurred for request %s to %s: %s', self::API_URI, $state, self::INSECURE_TLS_SSL_ERROR);
    }
}
