<?php

namespace App\Http\Middleware\Validation;

use App\Exceptions\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

abstract class GenericMiddlewareValidation
{
    protected function getParams(Request $request, array $params): array
    {
        $parsedParams = [];

        foreach ($params as $paramName) {
            $parsedParams = array_merge($parsedParams, [$paramName => strtolower($request->route($paramName))]);
        }

        return $parsedParams;
    }

    protected function handler(Validator $validation): void
    {
        if (!$validation->fails()) {
            return;
        }

        throw new DomainException($validation->errors());
    }
}