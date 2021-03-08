<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $faker;

    public function setUp() : void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}









// h: DOKUMENTASI
// RefreshDatabase adalah trait untuk me refresh/reset db dari awal
// dari migration nya atau table2, jadi dibuat clean dulu baru test dijalankan
// karena testing harus independent, artinya data tidak boleh terpengaruh test case yang lain

// kita juga buat faker untuk data nya

// setUp(), method yang dipanggil ketika test dieksekusi
// parent::setUp memanggil method parent
