<?php

namespace App\Internal\Cache;

use Illuminate\Support\Facades\Cache;

class CountyCache
{
    public const CACHE_TEMPLATE = 'C%s_P%d_S%d';

    /**
     * Verify if there are saved cache for informed $countyCode, $pageNumber and $pageSize
     * @param string $stateCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return array|null
     */
    public static function verifyCache(string $stateCode, int $pageNumber, int $pageSize): ?array
    {
        $cacheName = self::buildName($stateCode, $pageNumber, $pageSize);
        $cacheResult = Cache::get($cacheName);

        if (!$cacheResult) {
            return null;
        }

        return $cacheResult;
    }

    /**
     * Save the $data in cache for the informed $stateCode, $pageNumber and $pageSize
     * @param string $stateCode
     * @param int $pageNumber
     * @param int $pageSize
     * @param array $data
     * @return void
     */
    public static function save(string $stateCode, int $pageNumber, int $pageSize, array $data): void
    {
        $cacheName = self::buildName($stateCode, $pageNumber, $pageSize);
        Cache::put($cacheName, $data, now()->addMinutes(10));
    }

    /**
     * Returns the cache name that follows the format: "C<county-code>_P<page-number>_S<page-size>"
     * @param string $stateCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return string
     */
    private static function buildName(string $stateCode, int $pageNumber, int $pageSize): string
    {
        return sprintf(self::CACHE_TEMPLATE, $stateCode, $pageNumber, $pageSize);
    }
}