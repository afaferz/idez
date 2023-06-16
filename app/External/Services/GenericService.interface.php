<?php

namespace App\External\Services;

interface GenericServiceInterface
{
    // public function errorHandler($message): void;
    // public function logHandler($message): void;
}

interface GetCountyInterface
{

    /**
     * @var string
     */
    public $state;
    /** 
     * @var number
     */
    public $page_size;
    /** 
     * @var number
     */
    public $page_number;
}
