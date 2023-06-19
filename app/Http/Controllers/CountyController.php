<?php

namespace App\Http\Controllers;

use App\External\Services\Abstractions\CountyServiceAbstract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountyGetRequest as Request;
use Illuminate\Http\JsonResponse;

class CountyController extends Controller
{
    public function __construct(private readonly CountyServiceAbstract $service)
    {
        // Constructor
    }

    /** */
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
        $response = $this->service->getCounty($requestParams);

        return $this->sendJson($response);
    }
}
