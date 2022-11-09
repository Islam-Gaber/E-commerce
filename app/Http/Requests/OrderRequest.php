<?php

namespace App\Http\Requests;

use App\Http\Requests\mainRequest;

class OrderRequest extends MainRequest
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
            'customer_id'  => 'required|integer',
            'zip_code'     => 'required|integer',
            'address'      => 'required|string',
            'phone'        => 'required|digits_between:10,11 |starts_with:(,040,010,011,012,0100)',
            'city'         => 'required|string',
        ];
    }
}
