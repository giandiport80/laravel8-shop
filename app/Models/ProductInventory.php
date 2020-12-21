<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'qty'];

    // 1 productInventory dimiliki 1 product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function reduceStock($productId, $qty)
    {
        $inventory = self::where('product_id', $productId)->firstOrFail();
        $inventory->qty = $inventory->qty - $qty;
        $inventory->save();
    }

    /**
     * Increase stock product
     *
     * @param int $productId product ID
     * @param int $qty       qty product
     *
     * @return void
     */
    public static function increaseStock($productId, $qty)
    {
        $inventory = self::where('product_id', $productId)->firstOrFail();
        $inventory->qty = $inventory->qty + $qty;
        $inventory->save();
    }
}
