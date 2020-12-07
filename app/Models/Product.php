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
        'user_id',
        'sku',
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

    // N product memiliki N categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public static function statuses()
    {
        return [
            0 => 'draft',
            1 => 'active',
            2 => 'inactive'
        ];
    }
}
