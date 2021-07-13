<?php

namespace App\Http\Requests;

class GetPostsRequest extends APIRequest
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
        return [
            'page' => 'required|integer',
            'limit' => 'required|integer',
            'user_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
