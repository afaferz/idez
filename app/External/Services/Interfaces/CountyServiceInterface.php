<?php

namespace App\External\Services\Interfaces;

interface CountyServiceInterface
{
    public function handleError(string $message): void;
    public function handleLog(string $message): void;
}
