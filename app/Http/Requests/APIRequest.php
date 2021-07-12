<?php
/**
 * Created by PhpStorm.
 * User: owlting
 * Date: 7/12/21
 * Time: 11:29 PM
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class APIRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response($validator->errors()));
    }
}
