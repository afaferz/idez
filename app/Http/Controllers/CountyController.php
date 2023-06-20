<?php

namespace App\Http\Controllers;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountyGetRequest as Request;
use App\Internal\Cache\CountyCache;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="County API", version="1.0.0")
 */
class CountyController extends Controller
{
    public function __construct(private readonly CountyServiceAbstract $service)
    {
    }

    /**
     * @OA\Get(
     *     tags={"counties"},
     *     summary="Returns a list of counties",
     *     description="Returns a object of county",
     *     path="/api/v1/county/{state_code}",
     *      @OA\Parameter(
     *         name="state_code",
     *         in="path",
     *         description="State Code",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         description="Page Size",
     *         required=false,
     *     ),
     *      @OA\Parameter(
     *         name="page_number",
     *         in="query",
     *         description="Page Number",
     *         required=false,
     *     ),
     *     @OA\Response(response="200", description="A list of counties with name and ibge_code"),
     *     @OA\Response(response="400", description="Error from Service API"),
     *     @OA\Response(response="422", description="Error from County API"),
     *     @OA\Response(response="500", description="Internal Api Error"),
     * ),
     * 
     */
    public function index(Request $request): JsonResponse
    {
        $request->all();
        $stateCode = strtolower($request->route('state_code'));
        $pageNumber = $request->query("page_number") ?? 1;
        $pageSize = $request->query("page_size") ?? 50;
        $requestParams = [
            'state_code' => $stateCode,
            'page_number' => $pageNumber,
            'page_size' => $pageSize,

        ];

        $cacheResult = CountyCache::verifyCache($stateCode, $pageNumber, $pageSize);

        if ($cacheResult) {
            return $this->sendJson($cacheResult);
        }
        $response = $this->service->getCounty($requestParams);

        return $this->sendJson($response);
    }
}
