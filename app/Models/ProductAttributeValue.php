<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'attribute_id',
        'text_value',
        'boolean_value',
        'integer_value',
        'float_value',
        'datetime_value',
        'date_value',
        'json_value'
    ];

    // N productAttributeValues dimiliki 1 product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // mendapatkan value options pada product configurable
    public static function getAttributeOptions($product, $attributeCode)
    {
        $productVariantIDs = $product->variants->pluck('id');
        $attribute = Attribute::where('code', $attributeCode)->first();

        $attributeOptions = ProductAttributeValue::where('attribute_id', $attribute->id)
            ->whereIn('product_id', $productVariantIDs)
            ->get();

        return $attributeOptions;
    }
}
