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

    public const DRAFT = 0;
    public const ACTIVE = 1;
    public const INACTIVE = 2;

    public const STATUSES = [
        self::DRAFT => 'draft',
        self::ACTIVE => 'active',
        self::INACTIVE => 'inactive',
    ];

    public const SIMPLE = 'simple';
    public const CONFIGURABLE = 'configurable';
    public const TYPES = [
        self::SIMPLE => 'Simple',
        self::CONFIGURABLE => 'Configurable',
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
        return $this->hasMany(Product::class, 'parent_id')->orderBy('price', 'asc');
    }

    // 1 product dimiliki 1 parent product
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    // 1 product memiliki N productAttributeValues
    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class, 'parent_product_id');
    }

    // 1 product memiliki N productImages
    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('id', 'desc');
    }

    // label untuk status product
    public static function statuses()
    {
        return self::STATUSES;
    }

    // label untuk type product
    public static function types()
    {
        return self::TYPES;
    }

    // label tampilan status
    public function statusLabel()
    {
        $statuses = $this->statuses();

        return isset($this->status) ? $statuses[$this->status] : null;
    }

    // query mencari product yang parent_id = null
    public function scopeActive($query) // .. 1
    {
        return $query->where('status', 1)
            ->where('parent_id', null);
    }

    // label untuk menampilkan harga
    public function priceLabel()
    {
        return ($this->variants->count() > 0) ? $this->variants->first()->price : $this->price;
    }

    // label untuk menampilkan harga
    public function price_label()
    {
        return ($this->variants->count() > 0) ? $this->variants->first()->price : $this->price;
    }

    // cek apakah product tersebut configurable atau tidak
    public function configurable()
    {
        return $this->type == 'configurable';
    }

    // cek apakah product tersebut simple atau tidak
    public function simple()
    {
        return $this->type == 'simple';
    }

    // product pupular berdasarkan product yg banyak terjual bulan sekarang
    public function scopePopular($query, $limit = 10)
    {
        $month = now()->format('m');

        return $query->selectRaw('products.*, COUNT(order_items.id) as total_sold')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereRaw(
                'orders.status = :order_satus AND MONTH(orders.order_date) = :month',
                [
                    'order_status' => Order::COMPLETED,
                    'month' => $month
                ]
            )
            ->groupBy('products.id')
            ->orderByRaw('total_sold DESC')
            ->limit($limit);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}









// p: clue 1
// membuat query di model yang bisa kita panggil method nya
// dibuat dengan awalan scope + nama method nya
