<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'parent_id'];

    // 1 category memiliki N child category
    public function childs() // .. 1
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // N child category dimiliki 1 category parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // N category memiliki N product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    // query mencari category bedaraskan parent category
    public function scopeParentCategories($query)
    {
        return $query->where('parent_id', 0);
    }

    // query mengikutsertakan child category berdasarkan parent category nya
    public static function childIds($parentId = 0)
    {
        $categories = Category::select('id', 'name', 'parent_id')->where('parent_id', $parentId)->get()->toArray();

        $childIds = [];
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $childIds[] = $category['id'];
                $childIds = array_merge($childIds, Category::childIds($category['id']));
            }
        }

        return $childIds;
    }
}










// h: DOKUMENTASI

// p: clue 1
// childs()
// adalah relasi yang terjadi dalam category itu sendiri
// untuk mendapatkan child category dari category yang kita definisikan
// 1 category dapat memiliki banyak category child / anak
