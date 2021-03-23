<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attributes = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'attributes' => $this->attributes,
            'conditions' => $this->conditions,
            'product' => new ProductResource($this->associatedModel)
        ];

        return $attributes;
    }
}
