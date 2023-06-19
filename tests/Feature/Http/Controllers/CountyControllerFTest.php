<?php

namespace Tests\Feature\Http\Controllers;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\External\Services\BrasilApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CountyControllerFTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 200.
     * - Parse result to JSON
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiSuccess(): void
    {
        $stateCode = 'DF';
        $externalApiResponse = [
            [
                'nome' => 'BRASILIA',
                'codigo_ibge' => '5300108',
            ]
        ];

        $expectedResponse = [
            [
                "name" => "BRASILIA",
                "ibge_code" => "5300108",
            ],
        ];

        // Brasil Api request
        Http::fake([
            'https://brasilapi.com.br/api/*' => Http::response($externalApiResponse, 200)
        ]);
        $response = Http::get('https://brasilapi.com.br/api//ibge/municipios/v1/df');
        $this->assertEquals($externalApiResponse, $response->json());

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $serviceResponse = $this->get('/api/v1/county/' . $stateCode);
        $serviceResponse->assertStatus(200);
        $decodedResponse = (json_decode($serviceResponse->getContent(), true));
        $this->assertEquals($expectedResponse, $decodedResponse);
    }
    /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 200.
     * - Parse result to JSON
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiWithInsensitiveCaseSuccess(): void
    {
        $statesCodeInsensitive = ['DF', 'df', 'Df', 'dF'];
        $stateCode = $statesCodeInsensitive[array_rand($statesCodeInsensitive)];
        $externalApiResponse = [
            [
                'nome' => 'BRASILIA',
                'codigo_ibge' => '5300108',
            ]
        ];

        $expectedResponse = [
            [
                "name" => "BRASILIA",
                "ibge_code" => "5300108",
            ],
        ];

        // Brasil Api request
        Http::fake([
            'https://brasilapi.com.br/api/*' => Http::response($externalApiResponse, 200)
        ]);
        $response = Http::get('https://brasilapi.com.br/api//ibge/municipios/v1/df');
        $this->assertEquals($externalApiResponse, $response->json());

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $serviceResponse = $this->get('/api/v1/county/' . $stateCode);
        $serviceResponse->assertStatus(200);
        $decodedResponse = (json_decode($serviceResponse->getContent(), true));
        $this->assertEquals($expectedResponse, $decodedResponse);
    }
    /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 404.
     * - Parse result to JSON
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiWithNoMatchRouteRuleSuccess(): void
    {
        $stateCode = 'asd';

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $serviceResponse = $this->get('/api/v1/county/' . $stateCode);
        $serviceResponse->assertStatus(404);
    }
     /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 404.
     * - Parse result to JSON
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiWithNoMatchStateCodeSuccess(): void
    {
        $stateCode = 'CC';
        $externalApiResponse = [
            [
                'nome' => 'BRASILIA',
                'codigo_ibge' => '5300108',
            ]
        ];

        $expectedResponse = [
            "error" => [
                  "state_code" => [
                     "Invalid State Code", 
                     "Allowed values [al,ap,am,ba,ce,df,es,go,ma,mt,ms,mg,pa,pb,pr,pe,pi,rj,rn,rs,ro,rr,sc,sp,se,to]" 
                  ] 
               ] 
         ];

        // Brasil Api request
        Http::fake([
            'https://brasilapi.com.br/api/*' => Http::response($externalApiResponse, 200)
        ]);
        $response = Http::get('https://brasilapi.com.br/api//ibge/municipios/v1/df');
        $this->assertEquals($externalApiResponse, $response->json());

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $serviceResponse = $this->get('/api/v1/county/' . $stateCode);
        $serviceResponse->assertStatus(422);
        $decodedResponse = (json_decode($serviceResponse->getContent(), true));
        $this->assertEquals($expectedResponse, $decodedResponse);
    }
    /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 200.
     * - Parse result to JSOn
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiWithPaginationSuccess(): void
    {
        $stateCode = 'BA';
        $pageNumber = 1;
        $pageSize = 2;
        $externalApiResponse = [
            [
                "nome" => "ABAIRA",
                "codigo_ibge" => "2900108"
            ],
            [
                "nome" => "ABARE",
                "codigo_ibge" => "2900207"
            ],
            [
                "nome" => "ACAJUTIBA",
                "codigo_ibge" => "2900306"
            ],
            [
                "nome" => "ADUSTINA",
                "codigo_ibge" => "2900355"
            ],
            [
                "nome" => "AGUA FRIA",
                "codigo_ibge" => "2900405"
            ],
            [
                "nome" => "ERICO CARDOSO",
                "codigo_ibge" => "2900504"
            ],
            [
                "nome" => "AIQUARA",
                "codigo_ibge" => "2900603"
            ],
            [
                "nome" => "ALAGOINHAS",
                "codigo_ibge" => "2900702"
            ],
            [
                "nome" => "ALCOBACA",
                "codigo_ibge" => "2900801"
            ],
            [
                "nome" => "ALMADINA",
                "codigo_ibge" => "2900900"
            ],
            [
                "nome" => "AMARGOSA",
                "codigo_ibge" => "2901007"
            ],
            [
                "nome" => "AMELIA RODRIGUES",
                "codigo_ibge" => "2901106"
            ],
            [
                "nome" => "AMERICA DOURADA",
                "codigo_ibge" => "2901155"
            ],
            [
                "nome" => "ANAGE",
                "codigo_ibge" => "2901205"
            ],
            [
                "nome" => "ANDARAI",
                "codigo_ibge" => "2901304"
            ],
            [
                "nome" => "ANDORINHA",
                "codigo_ibge" => "2901353"
            ],
            [
                "nome" => "ANGICAL",
                "codigo_ibge" => "2901403"
            ],
            [
                "nome" => "ANGUERA",
                "codigo_ibge" => "2901502"
            ],
            [
                "nome" => "ANTAS",
                "codigo_ibge" => "2901601"
            ],
            [
                "nome" => "ANTONIO CARDOSO",
                "codigo_ibge" => "2901700"
            ],
            [
                "nome" => "ANTONIO GONCALVES",
                "codigo_ibge" => "2901809"
            ],
            [
                "nome" => "APORA",
                "codigo_ibge" => "2901908"
            ],
            [
                "nome" => "APUAREMA",
                "codigo_ibge" => "2901957"
            ],
            [
                "nome" => "ARACATU",
                "codigo_ibge" => "2902005"
            ],
            [
                "nome" => "ARACAS",
                "codigo_ibge" => "2902054"
            ],
            [
                "nome" => "ARACI",
                "codigo_ibge" => "2902104"
            ],
            [
                "nome" => "ARAMARI",
                "codigo_ibge" => "2902203"
            ],
            [
                "nome" => "ARATACA",
                "codigo_ibge" => "2902252"
            ],
            [
                "nome" => "ARATUIPE",
                "codigo_ibge" => "2902302"
            ],
            [
                "nome" => "AURELINO LEAL",
                "codigo_ibge" => "2902401"
            ],
            [
                "nome" => "BAIANOPOLIS",
                "codigo_ibge" => "2902500"
            ],
            [
                "nome" => "BAIXA GRANDE",
                "codigo_ibge" => "2902609"
            ],
            [
                "nome" => "BANZAE",
                "codigo_ibge" => "2902658"
            ],
            [
                "nome" => "BARRA",
                "codigo_ibge" => "2902708"
            ],
            [
                "nome" => "BARRA DA ESTIVA",
                "codigo_ibge" => "2902807"
            ],
            [
                "nome" => "BARRA DO CHOCA",
                "codigo_ibge" => "2902906"
            ],
            [
                "nome" => "BARRA DO MENDES",
                "codigo_ibge" => "2903003"
            ],
            [
                "nome" => "BARRA DO ROCHA",
                "codigo_ibge" => "2903102"
            ],
            [
                "nome" => "BARREIRAS",
                "codigo_ibge" => "2903201"
            ],
            [
                "nome" => "BARRO ALTO",
                "codigo_ibge" => "2903235"
            ],
            [
                "nome" => "BARROCAS",
                "codigo_ibge" => "2903276"
            ],
            [
                "nome" => "BARRO PRETO",
                "codigo_ibge" => "2903300"
            ],
            [
                "nome" => "BELMONTE",
                "codigo_ibge" => "2903409"
            ],
            [
                "nome" => "BELO CAMPO",
                "codigo_ibge" => "2903508"
            ],
            [
                "nome" => "BIRITINGA",
                "codigo_ibge" => "2903607"
            ],
            [
                "nome" => "BOA NOVA",
                "codigo_ibge" => "2903706"
            ],
            [
                "nome" => "BOA VISTA DO TUPIM",
                "codigo_ibge" => "2903805"
            ],
            [
                "nome" => "BOM JESUS DA LAPA",
                "codigo_ibge" => "2903904"
            ],
            [
                "nome" => "BOM JESUS DA SERRA",
                "codigo_ibge" => "2903953"
            ],
            [
                "nome" => "BONINAL",
                "codigo_ibge" => "2904001"
            ]
        ];

        $expectedResponse =  [
            [
                "name" => "ABAIRA",
                "ibge_code" => "2900108"
            ],
            [
                "name" => "ABARE",
                "ibge_code" => "2900207"
            ]
        ];

        // Brasil Api request
        Http::fake([
            'https://brasilapi.com.br/api/*' => Http::response($externalApiResponse, 200)
        ]);
        $response = Http::get('https://brasilapi.com.br/api//ibge/municipios/v1/ba');
        $this->assertEquals($externalApiResponse, $response->json());

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $url = "/api/v1/county/{$stateCode}?page_size={$pageSize}&page_number=$pageNumber";
        $serviceResponse = $this->get($url);
        $serviceResponse->assertStatus(200);
        $decodedResponse = (json_decode($serviceResponse->getContent(), true));
        $this->assertEquals($expectedResponse, $decodedResponse);
    }
    /**
     * Tests the scenary where:
     * - Fake request to Brasil API.
     * - Brasil API returns the data.
     * - Parse data.
     * - Return status code 400.
     * - Parse result to JSON
     *
     * @return void
     */
    public function testCountyControllerWithBrasilApiError(): void
    {
        $stateCode = 'DF';
        $externalApiResponse = ['error' => 'An error occured'];

        $expectedResponse =  [
            "error" => [
                "message" => "An Error Ocurred on Reques to https://brasilapi.com.br/api/ for df",
                "status_code" => 400
            ]
        ];

        // Brasil Api request
        Http::fake([
            'https://brasilapi.com.br/api/*' => Http::response($externalApiResponse, 400)
        ]);
        $response = Http::get('https://brasilapi.com.br/api//ibge/municipios/v1/df');
        $this->assertEquals($externalApiResponse, $response->json());

        // Brasil Service request
        $this->setExternalService(BrasilApiService::class);

        $serviceResponse = $this->get('/api/v1/county/' . $stateCode);
        $serviceResponse->assertStatus(400);
        $decodedResponse = (json_decode($serviceResponse->getContent(), true));
        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    private function setExternalService(string $service): void
    {
        $externalApi = new $service(new Http());

        $this->app->bind(
            CountyServiceAbstract::class,
            fn () => $externalApi
        );
    }
}
