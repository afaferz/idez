<?php

namespace Tests\Unit\Services;

use App\External\Services\IbgeService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IbgeApiServiceUTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    // /**
    //  * Test scenery with a not successful HTTP Request to Brasil API
    //  *
    //  * @return void
    //  */
    public function testGetInfoByCountyCodeException(): void
    {
        $requestParams = [
            'state_code' => 'DF',
            'page_number' => 1,
            'page_size' => 1,
        ];
        $externalApiResponse = $externalApiResponse = ['error' => 'An error occured'];

        // Brasil Api request
        Http::fake([
            'https://servicodados.ibge.gov.br/api/v1/*' => Http::response($externalApiResponse, 400)
        ]);
        $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/df/municipios');
        $this->assertEquals($externalApiResponse, $response->json());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An Error Ocurred for request https://servicodados.ibge.gov.br/api/v1/ to DF: Could not request due insecure TLS and SSL version.');
        $service = $this->createInstance();
        $service->getCounty($requestParams);
    }

    private function createInstance(): IbgeService
    {
        return new IbgeService(new Http);
    }
}
