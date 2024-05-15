<?php

namespace App\Http\Requests;

use App\Constants\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PemesananRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'vehicle_id'  => 'required',
            'date'  => 'required',
            'user_driver_id'  => 'required',
        ];
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        activity()
            ->withProperties([
                'user_id' => auth()->id(),
                'user_role' => authUser()->role,
                'error' => $validator->errors()
            ])
            ->log('Error booking request');
        throw new HttpResponseException(response()->error($validator->errors(), 422));
    }
}
