<?php

use App\External\Service\GenericService;
use App\External\Services\CountyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;

class GetCountyController extends Controller
{
    public function __construct(private readonly GenericService $service)
    {
    }
    public function __invoke(Request $request): JsonResponse
    {
        $stateCode = strtolower($request->route('state_code'));
        $page_number = $request->query("page_number") ?? 1;
        $page_size = $request->query("page_size") ?? 50;
        $requestParams = (object) [
            'state' => $stateCode,
            'page_number' => $page_number,
            'page_size' => $page_size,

        ];

        $response = $this->service->getCounty($requestParams);

        return $this->sendJson($response);
    }
}
