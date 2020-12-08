<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'validation',
        'is_required',
        'is_unique',
        'is_filterable',
        'is_configurable',
    ];

    // 1 attribute memiliki N attributeOptions
    public function attributeOptions()
    {
        return $this->hasMany(AttributeOption::class);
    }

    // label input type
    public static function types()
    {
        return [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'price' => 'Price',
            'boolean' => 'Boolean',
            'select' => 'Select',
            'datetime' => 'Datetime',
            'date' => 'Date'
        ];
    }

    // label input option pada is_required, is_unique, dll
    public static function booleanOptions()
    {
        return [
            1 => 'Yes',
            0 => 'No'
        ];
    }

    // label untuk input validation
    public static function validations()
    {
        return [
            'number' => 'Number',
            'decimal' => 'Decimal',
            'email' => 'Email',
            'url' => 'URL'
        ];
    }
}
