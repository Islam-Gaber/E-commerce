<?php

namespace App\Http\Requests;

use App\Http\Requests\mainRequest;

class CartRequest extends MainRequest
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
            'order_id'        => 'required|integer',
            'product_id'      => 'required|integer',
            'custom'          => 'required|integer',
            'quantity'        => 'required|integer',
        ];
    }
}
