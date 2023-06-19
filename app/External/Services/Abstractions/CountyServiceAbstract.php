<?php

namespace App\External\Services\Abstractions;

use App\External\Services\Interfaces\CountyServiceInterface;
use Illuminate\Support\Facades\Log;
use DomainException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class CountyServiceAbstract implements CountyServiceInterface
{
    public function __construct(protected Http $http)
    {
        // Constructor
    }

    public function getCounty(array $requestParams)
    {
        $stateCode = $requestParams['state_code'];
        $pageSize = $requestParams['page_size'];
        $pageNumber = $requestParams['page_number'];

        $pageInfo = (object) [
            'page_size' => (int)$pageSize,
            'page_number' => (int)$pageNumber
        ];
        $response = $this->getCounties($stateCode);
        $paginated = $this->paginate($response, $pageInfo);

        return $paginated;
    }

    private function paginate(array $data, object $pageInfo)
    {
        $pageNumber = $pageInfo->page_number;
        $pageSize = $pageInfo->page_size;
        $offsetPage = ($pageNumber - 1) * $pageSize;

        return array_slice($data, $offsetPage, $pageSize);
    }

    protected function decodeJson(Response $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    public function handleError(string $message): void
    {
        throw new DomainException($message);
    }

    public function handleLog(string $message): void
    {
        Log::error($message);
    }


    protected abstract function getCounties(string $state): array;
}
