<?php

namespace App;

use Illuminate\Support\Arr;

trait Authorizable
{
    private $abilities = [ // .. 1
        'index' => 'view',
        'edit' => 'edit',
        'show' => 'view',
        'update' => 'edit',
        'create' => 'add',
        'store' => 'add',
        'destroy' => 'delete',

        'options' => 'add',
        'store_option' => 'add',
        'edit_option' => 'edit',
        'update_option' => 'edit',
        'remove_option' => 'edit'
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if ($ability = $this->getAbility($method)) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    public function getAbility($method)
    {
        $routeName = explode('.', request()->route()->getName()); // .. 2
        $action = Arr::get($this->getAbilities(), $method);

        return $action ? $action . '_' . $routeName[0] : null;
    }

    private function getAbilities()
    {
        return $this->abilities;
    }

    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}










// h: DOKUMENTASI

// ketika kita ingin menambahkan manual suatu user memiliki permission tertentu di dalam db
// jangan lupa gunakan perintah ini
// > php artisan cache:forget spatie.permission.cache


// p: clue 1
// nama method pada controller yang nilanya sama dengan prefix dari permission

// p: clue 2
// akan mengambil nama dari routing
// jadi routing nya harus memiliki nama
