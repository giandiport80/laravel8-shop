<?php

namespace App\Repositories\Admin;

use App\Models\Attribute;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;

class AttributeRepository implements AttributeRepositoryInterface
{
    /**
     * getConfigurableAttributes
     * query attribute yang configurable nya true
     *
     * @return void
     */
    public function getConfigurableAttributes()
    {
        return Attribute::where('is_configurable', true)->get();
    }
}
