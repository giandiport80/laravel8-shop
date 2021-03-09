<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    // .. ==================== POSITIIVE TESTING =========================

    /**
     * test role for admin, operator, user
     *
     * @var mixed
     */
    protected $admin, $operator, $user;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->_setupPermissions();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // dd($this->admin->toArray());

        $this->operator = User::factory()->create();
        $this->operator->assignRole('operator');

        // dd($this->operator->toArray());

        $this->user = User::factory()->create();

        // dd($this->user->toArray());
    }

    /**
     * _setupPermissions
     *
     * @return void
     */
    private function _setupPermissions()
    {
        $permissions = [
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        Role::findOrCreate('admin')->givePermissionTo($permissions);

        Role::findOrCreate('operator')->givePermissionTo(['view_categories']);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * testAdminCanViewCategoryIndex
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanViewTheCategoryIndex
     *
     * @return void
     */
    public function testAdminCanViewTheCategoryIndex()
    {
        $this->_setupCategories();

        // dd(Category::all());

        $response = $this->actingAs($this->admin)->get('admin/categories');

        $response->assertStatus(200);

        $response->assertSee('Category one');
        $response->assertSee('Category two');
    }

    /**
     * _setupCategories
     *
     * @return void
     */
    private function _setupCategories()
    {
        Category::factory()->create([
            'name' => 'Category one',
            'slug' => 'category-one'
        ]);

        Category::factory()->create([
            'name' => 'Category two',
            'slug' => 'category-two'
        ]);
    }

    /**
     * testAdminCanAddACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanAddACategory
     *
     * @return void
     */
    public function testAdminCanAddACategory()
    {
        $params = [
            'name' => $this->faker->words(2, true)
        ];

        // dd($params);

        $response = $this->actingAs($this->admin)->post('admin/categories', $params);

        $response->assertStatus(302);

        $category = Category::first();

        $this->assertEquals($params['name'], $category->name);
        $this->assertEquals(0, $category->parent_id);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category has been saved!');
    }

    /**
     * testAdminCanAddACategoryWithParent
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanAddACategoryWithParent
     *
     * @return void
     */
    public function testAdminCanAddACategoryWithParent()
    {
        $parentCategory = Category::factory()->create();

        $params = [
            'name' => $this->faker->words(2, true),
            'parent_id' => $parentCategory->id
        ];

        $response = $this->actingAs($this->admin)->post(route('categories.store'), $params);

        $response->assertStatus(302);

        $category = Category::where('id', '!=', $parentCategory->id)->first();

        $this->assertEquals($params['name'], $category->name);
        $this->assertEquals($params['parent_id'], $category->parent_id);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category has been saved!');
    }

    /**
     * testAdminCanUpdateACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanUpdateACategory
     *
     * @return void
     */
    public function testAdminCanUpdateACategory()
    {
        $existCategory = Category::factory()->create();

        $params = [
            'name' => 'New Category Name',
        ];

        $response = $this->actingAs($this->admin)->patch(route('categories.update', $existCategory->id), $params);

        $response->assertStatus(302);

        $response->assertRedirect(route('categories.index'));

        $updatedCategory = Category::find($existCategory->id);

        $this->assertEquals($params['name'], $updatedCategory->name);
        $this->assertEquals($existCategory->parent_id, $updatedCategory->parent_id);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category has been updated!');
    }

    /**
     * testAdminCanDeleteCategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanDeleteCategory
     *
     * @return void
     */
    public function testAdminCanDeleteCategory()
    {
        $existCategory = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('categories.destroy', $existCategory->id));

        $response->assertStatus(302);

        $categories = Category::all();

        $this->assertCount(0, $categories);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Category has been deleted!');
    }

    /**
     * testOperatorCanViewTheCategoryIndex
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testOperatorCanViewTheCategoryIndex
     *
     * @return void
     */
    public function testOperatorCanViewTheCategoryIndex()
    {
        $this->_setupCategories();

        // dd(Category::all());

        $response = $this->actingAs($this->operator)->get('admin/categories');

        $response->assertStatus(200);

        $response->assertSee('Category one');
        $response->assertSee('Category two');
    }

    // .. ==================== NEGATIVE TESTING =========================

    /**
     * testAdminCanNotAddDuplicateCategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanNotAddDuplicateCategory
     *
     * @return void
     */
    public function testAdminCanNotAddDuplicateCategory()
    {
        $existCategory = Category::factory()->create();

        $params = [
            'name' => $existCategory->name
        ];

        $response = $this->actingAs($this->admin)->post(route('categories.store'), $params);

        $response->assertStatus(302);

        $categories = Category::get();

        // dd($categories->toArray());

        $this->assertCount(1, $categories);

        $errors = session('errors');

        // dd($errors);

        $response->assertSessionHasErrors();
        $this->assertEquals('The name has already been taken.', $errors->get('name')[0]);
    }

    /**
     * testAdminCanNotAddCategoryWithBlankName
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testAdminCanNotAddCategoryWithBlankName
     *
     * @return void
     */
    public function testAdminCanNotAddCategoryWithBlankName()
    {
        $params = [];

        $response = $this->actingAs($this->admin)->post(route('categories.store'), $params);

        $response->assertStatus(302);

        $categories = Category::get();

        // dd($categories->toArray());

        $this->assertCount(0, $categories);

        $errors = session('errors');

        // dd($errors);

        $response->assertSessionHasErrors();
        $this->assertEquals('The name field is required.', $errors->get('name')[0]);
    }

    /**
     * testOperatorCanNotAddACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testOperatorCanNotAddACategory
     *
     * @return void
     */
    public function testOperatorCanNotAddACategory()
    {
        $params = [
            'name' => $this->faker->words(2, true)
        ];

        $response = $this->actingAs($this->operator)->post(route('categories.store', $params));

        $response->assertStatus(403);
    }

    /**
     * testOperatorCanNotUpdateACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testOperatorCanNotUpdateACategory
     *
     * @return void
     */
    public function testOperatorCanNotUpdateACategory()
    {
        $existCategory = Category::factory()->create();

        $params = [
            'name' => 'test update'
        ];

        $response = $this->actingAs($this->operator)->patch(route('categories.update', $existCategory->id), $params);

        $response->assertStatus(403);
    }

    /**
     * testOperatorCanNotDeleteACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testOperatorCanNotDeleteACategory
     *
     * @return void
     */
    public function testOperatorCanNotDeleteACategory()
    {
        $existCategory = Category::factory()->create();

        $response = $this->actingAs($this->operator)->delete(route('categories.destroy', $existCategory->id));

        $response->assertStatus(403);
    }

    /**
     * testUserCanNotViewTheCategoryIndex
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testUserCanNotViewTheCategoryIndex
     *
     * @return void
     */
    public function testUserCanNotViewTheCategoryIndex()
    {
        $this->_setupCategories();

        $response = $this->actingAs($this->user)->get(route('categories.index'));

        $response->assertStatus(403);
    }

    /**
     * testUserCanNotAddACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testUserCanNotAddACategory
     *
     * @return void
     */
    public function testUserCanNotAddACategory()
    {
        $params = [
            'name' => $this->faker->words(2, true)
        ];

        $response = $this->actingAs($this->user)->post(route('categories.store'), $params);

        $response->assertStatus(403);
    }

    /**
     * testUserCanNotUpdateACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testUserCanNotUpdateACategory
     *
     * @return void
     */
    public function testUserCanNotUpdateACategory()
    {
        $existCategory = Category::factory()->create();

        $params = [
            'name' => 'Test update'
        ];

        $response = $this->actingAs($this->user)->patch(route('categories.update', $existCategory->id), $params);

        $response->assertStatus(403);
    }

    /**
     * testUserCanNotDeleteACategory
     * php artisan test tests/Feature/Admin/CategoryTest.php --filter testUserCanNotDeleteACategory
     * @return void
     */
    public function testUserCanNotDeleteACategory()
    {
        $existCategory = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('categories.destroy', $existCategory->id));

        $response->assertStatus(403);
    }
}









// h: DOKUMENTASI

// * teori
/*
  --- Expected
  +++ Actual
  @@ @@
  -'The name has already been taken.'
  +'The name field is required.'
*/

// ! penting
// sebelumnya kita import table migrations ke db laravel8_shop_testing
// karena ketika database di refresh, ia akan melihat migrationsnya
// jika tidak di dimport, maka error yg disebabkan struktur table akan muncul
// sehingga membuat error testing nya
// jangan menjalankan test pada 2 terminal, karena akan menggangu test sebelumnya
// juga karena pada saat test berlangsung, database akan di refresh

// > php artisan test tests/Feature/Admin/CategoryTest.php

// agar permission berjalan pada test, kita harus registrasikan ke app
// $this->app->make(PermissionRegistrar::class)->registerPermissions();

// pada method testAdminCanViewCategoryIndex()
// method ini untuk mengakses url admin/categories dengan role sebagai admin

// ketika url yang dituju tidak ada, maka akan dapat pesan error seperti ini
//   Expected status code 200 but received 404.
//   Failed asserting that 200 is identical to 404.

// memastikan admin bisa melihat category yang tersimpan
// $response->assertSee('Category one');

// $response->assertStatus(302);
// 302 artinya di redirect ke halaman lain

// $response->assertRedirect(route('categories.index'));
// memastikan halaman di redirech ke halaman admin/categories

// $response->assertSessionHas('success', 'Category has been saved');
// memastikan ada session message success

// $response->assertSessionHasErrors();
// memastikan ada error

//   Expected status code 302 but received 403.
//   Failed asserting that 302 is identical to 403.
// error disini artinya 403 tidak memiliki hak akses / unauthorized
