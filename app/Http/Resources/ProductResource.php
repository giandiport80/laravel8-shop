<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'type' => $this->type,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->priceLabel(),
            'feature_image' => $this->getFeaturedImage(),
            'short_description' => $this->short_description,
            'description' => $this->description
        ];

        if($this->type == 'configurable' && $this->variants->count() > 0){
            $attributes['variants'] = new ProductCollection($this->variants);
        }

        return $attributes;
    }

    private function getFeaturedImage()
    {
        return ($this->productImages->first()) ? asset('storage/' . $this->productImages->first()->path) : null;
    }
}











// h: DOKUMENTASI

// $attributes['variants'] = $this->variants;
// kita bisa saja gunakan ini pada saat product type nya configurable
// tetapi data nya akan keluar semua
// maka dari itu kita gunakan collection, karena sudah kita tentukan data mana yg keluar di resource
