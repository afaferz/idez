<?php

namespace App\Http\Requests;

use App\Exceptions\BadRequestException;
use App\Types\Enums\StateCodeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CountyGetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'state_code' => [new Enum(StateCodeEnum::class)],
            'page_number' => 'integer|max:100',
            'page_size' => 'integer|max:1000'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'state_code' => ['Invalid State Code', 'Allowed values ' . $this->getAllowedStates()],
            'page_number' => 'Max page_number value is 100',
            'page_size' => 'Max page_size value is 1000',
        ];
    }
    public function all($keys = null)
    {
        $lowedParams = array_map("strtolower", $this->route()->parameters());
        return array_replace_recursive(
            parent::all(),
            $lowedParams
        );
    }
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'error' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
    private function getAllowedStates()
    {
        $statesCode = json_encode(StateCodeEnum::cases());

        return str_replace(['\\', '"'], '', $statesCode);
    }
}
