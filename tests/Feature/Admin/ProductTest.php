<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected $admin;
    protected $operator;

    /**
     * Setup every thing before running the tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setupPermissions();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->operator = User::factory()->create();
        $this->operator->assignRole('operator');
    }

    /**
     * Setup the permissions
     *
     * @return void
     */
    private function setupPermissions()
    {
        $permissions = [
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        Role::findOrCreate('admin')
            ->givePermissionTo($permissions);

        Role::findOrCreate('operator')
            ->givePermissionTo(['view_products']);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Setup the products
     *
     * @return void
     */
    private function setupProducts()
    {
        Product::factory()->create(
            [
                'name' => 'Product one',
                'slug' => 'product-one',
            ]
        );

        Product::factory()->create(
            [
                'name' => 'Product two',
                'slug' => 'product-two',
            ]
        );
    }

    /**
     * Admin can view the product index
     *
     * @return void
     */
    public function testAdminCanViewTheProductIndex()
    {
        $this->withoutExceptionHandling();

        $this->setupProducts();

        $response = $this
            ->actingAs($this->admin)
            ->get('/admin/products');

        $response->assertStatus(200);
        $response->assertSee('Product one');
        $response->assertSee('Product two');
    }

    /**
     * Admin can add a simple product
     *
     * @return void
     */
    public function testAdminCanAddASimpleProduct()
    {
        $sku = $this->faker->isbn10;

        $params = [
            'sku' => $sku,
            'type' => 'simple',
            'name' => 'New simple product',
            'price' => $this->faker->randomFloat,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/products', $params);

        $response->assertStatus(302);

        $product = Product::first();

        $this->assertEquals($params['name'], $product->name);
        $this->assertEquals($sku, $product->sku);
        $this->assertEquals(0, $product->parent_id);

        $response->assertRedirect('/admin/products/' . $product->id . '/edit');
        $response->assertSessionHas('success', 'Product has been saved!');
    }
}
