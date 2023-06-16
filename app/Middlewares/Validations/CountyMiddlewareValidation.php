<?php

namespace App\Http\Middleware\Validation;

use App\Enums\CountyCodeEnum;
use App\Types\Enums\StateCodeEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class CountyMiddlewareValidation extends GenericMiddlewareValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestParameters = array_merge($this->getParams($request, ['state_code']), $request->all());

        $validation = FacadeValidator::make(
            $requestParameters,
            [
                'state_code' => [new Enum(CountyCodeEnum::class)],
                'page_number' => 'integer|max:100',
                'page_size' => 'integer|max:200'
            ],
            [
                'code' => [
                    'State code is not valid',
                    'Allowed values are: ' . $this->getAllowedCountyCodes(),
                ]
            ]
        );

        $this->handler($validation);

        return $next($request);
    }

    private function getAllowedCountyCodes(): string
    {
        $countyCodes = json_encode(StateCodeEnum::cases());

        return str_replace(['\\', '"'], '', $countyCodes);
    }
}