<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\mainResource;


class ProductResource extends mainResource
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
            'name'                      => $this->name,
            'description'               => $this->description,
            'price'                     => $this->price,
            'color'                     => $this->color,
            'prand'                     => $this->prand,
            'image'                     => $this->image,
            'category'                  => new CategoryResource($this->Category),
            'cart'                      => CartResource::collection($this->Cart),
            'user'                      => new UserResource($this->User),

            'state'                     => $this->state,
            'created_at'                => Carbon::parse($this->created_at)->setTimezone(config('time.zone'))->format(config('time.format')),
            'updated_at'                => Carbon::parse($this->updated_at)->setTimezone(config('time.zone'))->format(config('time.format'))
        ];
    }
}
