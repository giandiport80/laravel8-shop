<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'sku',
        'type',
        'name',
        'slug',
        'price',
        'weight',
        'length',
        'width',
        'height',
        'short_description',
        'description',
        'status'
    ];

    // N product dimiliki 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 product memiliki 1 productInventory
    public function productInventory()
    {
        return $this->hasOne(ProductInventory::class);
    }

    // N product memiliki N categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    // 1 product meiliki banyak variant (yg menghubungkannya parent_id)
    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    // 1 product dimiliki 1 parent product
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    // 1 product memiliki N productAttributeValues
    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    // 1 product memiliki N productImages
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    // label untuk status product
    public static function statuses()
    {
        return [
            0 => 'draft',
            1 => 'active',
            2 => 'inactive'
        ];
    }

    // label untuk type product
    public static function types()
    {
        return [
            'simple' => 'Simple',
            'configurable' => 'Configurable'
        ];
    }

    // label tampilan status
    public function statusLabel()
    {
        $statuses = $this->statuses();

        return isset($this->status) ? $statuses[$this->status] : null;
    }
}
