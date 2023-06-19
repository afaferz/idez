<?php

namespace Tests\External\Http\Controllers;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\External\Services\Factories\CountyServiceFactory as Factory;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CountyControllerETest extends TestCase
{
    /**
     * Tests the scenary where:
     * - We search information about county.
     * - Brasil API returns the data.
     * - We parse this data.
     * - We return parsed data with status code 200.
     *
     * @return void
     */
    public function testExtenalGetCountiesWithBrasilApiSuccess(): void
    {
        $stateCode = 'DF';
        $expectedResponse = self::getResultFromServiceApi('response_brasil_api.json');

        $this->setExternalServiceApiTo('brasil_api_service');

        $response = $this->get('/api/v1/county/' . $stateCode);

        $response->assertStatus(200);

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Tests the scenary where:
     * - We search information about county.
     * - Brasil API returns the data.
     * - We parse this data.
     * - We return parsed and paginated data with status code 200.
     *
     * @return void
     */
    public function testExtenalGetCountiesWithBrasilApiWithPaginationSuccess(): void
    {
        $countyCode = 'BA';
        $pageNumber = 1;
        $pageSize = 2;

        $expectedResponse = self::getResultFromServiceApi('response_brasil_api_paginate.json');

        $this->setExternalServiceApiTo('brasil_api_service');

        $response = $this->get("/api/v1/county/{$countyCode}?page_size={$pageSize}&page_number=$pageNumber");

        $response->assertStatus(200);

        $this->assertEquals($expectedResponse, $response->getContent());
    }


    // /**
    //  * Tests scenery where:
    //  * - We search information about county in IBGE API.
    //  * - Our service throws an exception, because the service is blocked.
    //  * - We return an HTTP error with status 400.
    //  *
    //  * @return void
    //  * @throws \Throwable
    //  */
    public function testExtenalGetCountiesWithIbgeApiError(): void
    {
        $countyCode = 'df';
        $expectedResponse = [
            "error" => [
                "message" => "An Error Ocurred for request https://servicodados.ibge.gov.br/api/v1/ to df: Could not request due insecure TLS and SSL version.",
                "status_code" => 400
            ]
        ];


        $this->setExternalServiceApiTo('ibge_api_service');

        $response = $this->get("/api/v1/county/{$countyCode}");
        $response->assertStatus(400);
        $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    private function setExternalServiceApiTo(string $serviceName): void
    {
        $this->app->bind(
            CountyServiceAbstract::class,
            fn () => Factory::create($serviceName)
        );
    }

    private static function getResultFromServiceApi(string $fileName): string
    {
        $json = file_get_contents(__DIR__ . '/../../data/' . $fileName);
        $noBreaklines = str_replace("\n", '', $json);
        $noWhitespace = str_replace(" ", "", $noBreaklines);
        return $noWhitespace;
    }
}
