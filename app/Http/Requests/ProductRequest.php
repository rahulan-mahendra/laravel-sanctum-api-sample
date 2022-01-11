<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');

        switch($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'name' => 'required|string|max:90|unique:products,name',
                        'slug' => 'required|string|max:90|unique:products,name',
                        'description' => 'nullable',
                        'price' => 'required',
                    ];
                }
            case 'PUT':
                {
                    return [
                        'name' => 'required|string|max:90|unique:products,name,'. $id,
                        'slug' => 'required|string|max:90|unique:products,name',
                        'description' => 'nullable',
                        'price' => 'required',
                    ];

                }
            default:break
                ;
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            "success" => false,
            "message" => "Operation failed.",
            "error" => $validator->errors()
        ],422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
