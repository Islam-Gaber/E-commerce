<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\mainResource;

class CartResource extends mainResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)

    {
        return [
            'id'                        => $this->id,
            'custom'                    => $this->custom,
            'size'                      => $this->size,
            'quantity'                  => $this->quantity,
            'product'                   => new ProductResource($this->Product),
            // 'order'                     => new OrderResource($this->Order),

            'state'                     => $this->state,
            'created_at'                => Carbon::parse($this->created_at)->setTimezone(config('time.zone'))->format(config('time.format')),
            'updated_at'                => Carbon::parse($this->updated_at)->setTimezone(config('time.zone'))->format(config('time.format'))
        ];
    }
}
