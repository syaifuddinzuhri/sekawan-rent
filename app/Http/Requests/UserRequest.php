<?php

namespace App\Http\Requests;

use App\Constants\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name'  => 'required',
            'role' => Rule::in(GlobalConstant::ROLES),
        ];
        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|min:6';
            $rules['email'] = 'required|unique:users,email,NULL,id,deleted_at,NULL';
        } else {
            $rules['email'] = ['required', Rule::unique('users')->ignore($this->route()->user, 'id')->whereNull('deleted_at')];
        }
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
            ->log('Error user request');
        throw new HttpResponseException(response()->error($validator->errors(), 422));
    }
}
