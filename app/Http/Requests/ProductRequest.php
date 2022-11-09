<?php

namespace App\Http\Requests;

use App\Http\Requests\mainRequest;

class ProductRequest extends MainRequest
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
            'category_id'    => 'required|integer',
            'user_id'        => 'required|integer',
            'name'           => 'required|string',
            'description'    => 'required|string',
            'price'          => 'required|integer',
            'color'          => 'required|string',
            'prand'          => 'required|string',
            'image'          => 'required|string',
        ];
    }
}
