<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attribute_id', 'name'];

    // N attributeOption dimiliki 1 attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
