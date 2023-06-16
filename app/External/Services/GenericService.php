<?php

namespace App\External\Service;

use App\External\Services\GenericServiceInterface;
use App\External\Services\GetCountyInterface;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;

abstract class GenericService implements GenericServiceInterface
{

    public function __construct(protected Http $http)
    {
    }
    protected function decodeJson(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    public function getCounty(GetCountyInterface $requestParams)
    {
        $state = $requestParams->state;
        $page_size = $requestParams->page_size;
        $page_number = $requestParams->page_number;

        $page_info = (object) [
            'page_size' => $page_size,
            'page_number' => $page_number
        ];
        $response = $this->getInfo($state);
        $paginated = $this->paginate($response, $page_info);

        return $paginated;
    }

    private function paginate(array $data, object $page_info)
    {
        $page_number = $page_info->number;
        $page_size = $page_info->size;
        $offset_page = ($page_size * 1) - $page_number;

        $paginated = array_slice($data, $offset_page, $page_size);
        return $paginated;
    }

    // protected function errorHandle($message): void
    // {
    //     throwException($message);
    // }
    // protected function logHandle($message): void
    // {
    //     throwException($message);
    // }

    protected abstract function getInfo(string $state): array;
}
