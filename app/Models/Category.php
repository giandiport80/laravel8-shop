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
}










// h: DOKUMENTASI

// p: clue 1
// childs()
// adalah relasi yang terjadi dalam category itu sendiri
// untuk mendapatkan child category dari category yang kita definisikan
// 1 category dapat memiliki banyak category child / anak
