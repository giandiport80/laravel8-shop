<?php

namespace Tests\Unit\Admin;

use App\Exceptions\CategoryNotFoundErrorException;
use App\Exceptions\CreateCategoryErrorException;
use App\Exceptions\UpdateCategoryErrorException;
use App\Models\Category;
use App\Repositories\Admin\CategoryRepository;
use Faker\Factory;
use Tests\TestCase; // kita extends ke TestCase.php
use Illuminate\Support\Str;

// use PHPUnit\Framework\TestCase;


class CategoryRepositoryTest extends TestCase
{
    // .. ==================== POSITIIVE TESTING =========================

    /**
     * A basic unit test example.
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanCreateCategory
     *
     * @return void
     */
    public function testCanCreateCategory()
    {
        $params = [
            'name' => $this->faker->words(2, true)
        ];

        $categoryRepository = new CategoryRepository(new Category());
        $category = $categoryRepository->create($params);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($params['name'], $category->name);
        $this->assertEquals(Str::slug($params['name']), $category->slug);
        $this->assertEquals(0, $category->parent_id);
    }

    /**
     * testCanCreateACategoryWithParent
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanCreateACategoryWithParent
     *
     * @return void
     */
    public function testCanCreateACategoryWithParent()
    {
        $name = $this->faker->words(2, true);

        $parentCategory = Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => 0
        ]);

        $params = [
            'name' => $this->faker->words(2, true),
            'parent_id' => $parentCategory->id
        ];

        $categoryRepository = new CategoryRepository(new Category());
        $category = $categoryRepository->create($params);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($params['name'], $category->name);
        $this->assertEquals(Str::slug($params['name']), $category->slug);
        $this->assertEquals($parentCategory->id, $category->parent_id);
    }

    /**
     * testCanDisplayAPaginatedCategories
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanDisplayAPaginatedCategories
     *
     * @return void
     */
    public function testCanDisplayAPaginatedCategories()
    {
        Category::factory()->count(10)->create();

        // $this->assertCount(10, Category::get()); // memastikan data yg dibuat 10

        $categoryRepository = new CategoryRepository(new Category());
        $paginatedCategories = $categoryRepository->paginate(5);

        $this->assertCount(5, $paginatedCategories);
    }

    /**
     * testCanFindCategoryById
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanFindCategoryById

     *
     * @return void
     */
    public function testCanFindCategoryById()
    {
        $newCategory = Category::factory()->create();

        $categoryRepository = new CategoryRepository(new Category());
        $category = $categoryRepository->findById($newCategory->id);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($newCategory->name, $category->name);
        $this->assertEquals($newCategory->slug, $category->slug);
        $this->assertEquals($newCategory->parent_id, $category->parent_id);
    }

    /**
     * testCanGetCategoriesAsADropdown
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanGetCategoriesAsADropdown
     *
     * @return void
     */
    public function testCanGetCategoriesAsADropdown()
    {
        Category::factory()->count(3)->create();

        $categories = Category::orderBy('name')->get();

        $categoryRepository = new CategoryRepository(new Category());
        $categoryDropdown = $categoryRepository->getCategoryDropDown();

        $this->assertEquals($categories, $categoryDropdown);
    }

    /**
     * testCanGetCategoriesAsADropownWithExceptionForACertainCategory
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanGetCategoriesAsADropownWithExceptionForACertainCategory
     *
     * @return void
     */
    public function testCanGetCategoriesAsADropownWithExceptionForACertainCategory()
    {
        Category::factory()->count(3)->create();

        $firstCategory = Category::first();

        $categoryRepository = new CategoryRepository(new Category());
        $categoryDropdown = $categoryRepository->getCategoryDropDown($firstCategory->id);

        $categories = Category::where('id', '!=', $firstCategory->id)->orderBy('name')->get();

        $this->assertEquals($categories, $categoryDropdown);
    }

    /**
     * testCanUpdateCategory
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanUpdateCategory
     *
     * @return void
     */
    public function testCanUpdateCategory()
    {
        $existCategory = Category::create([
            'name' => 'Old Name',
            'slug' => 'old-name',
            'parent_id' => 0
        ]);

        $params = [
            'name' => 'New Name'
        ];

        $categoryRepository = new CategoryRepository(new Category());
        $categoryRepository->update($params, $existCategory);

        $updatedCategory = Category::find($existCategory->id);

        $this->assertEquals($existCategory->id, $updatedCategory->id);
        $this->assertEquals('New Name', $updatedCategory->name);
        $this->assertEquals('new-name', $updatedCategory->slug);
    }

    /**
     * testCanDeleteCategory
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testCanDeleteCategory
     *
     * @return void
     */
    public function testCanDeleteCategory()
    {
        $category = Category::factory()->create();

        $this->assertCount(1, Category::get());

        $categoryRepository = new CategoryRepository(new Category());
        $categoryRepository->delete($category);

        $this->assertCount(0, Category::get());
    }

    // .. ==================== NEGATICE TESTING =========================

    /**
     * testShouldThrowAnErrorWhenCreateACategoryAndTheRequiredField
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testShouldThrowAnErrorWhenCreateACategoryAndTheRequiredField
     *
     * @return void
     */
    public function testShouldThrowAnErrorWhenCreateACategoryAndTheRequiredField()
    {
        $this->expectException(CreateCategoryErrorException::class);

        $params = [];

        $categoryRepository = new CategoryRepository(new Category());
        $categoryRepository->create($params);
    }

    /**
     * testShouldThrowAnErrorWhenUpdatedCategoryAndTheRequiredField
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testShouldThrowAnErrorWhenUpdatedCategoryAndTheRequiredField
     *
     * @return void
     */
    public function testShouldThrowAnErrorWhenUpdatedCategoryAndTheRequiredField()
    {
        $this->expectException(UpdateCategoryErrorException::class);

        $category = Category::factory()->create();

        $params = [];

        $categoryRepository = new CategoryRepository(new Category());
        $categoryRepository->update($params, $category);
    }

    /**
     * testShouldThrowAnErrorWhenGettingCategoryByInvalidId
     * php artisan test tests/Unit/Admin/CategoryRepositoryTest.php --filter testShouldThrowAnErrorWhenGettingCategoryByInvalidId
     *
     * @return void
     */
    public function testShouldThrowAnErrorWhenGettingCategoryByInvalidId()
    {
        $this->expectException(CategoryNotFoundErrorException::class);

        Category::factory()->create();

        $categoryRepository = new CategoryRepository(new Category());
        $categoryRepository->findById(123);
    }
}









// h: DOKUMENTASI

// ..
// $categoryRepository = new CategoryRepository(new Category());
// $category = $categoryRepository->create($params);
//  App\Repositories\Admin\CategoryRepository::create(): Argument #1 ($request) must be of type App\Http\Requests\CategoryRequest, array given,
// ini akan error, karena $request harus merupakan instance CategoryRequest, disini kita hanya berikan array
// maka dari itu kita harus ubah

// hasil test
//    WARN  Tests\Unit\Admin\CategoryRepositoryTest
//   ! can create category → This test did not perform any assertions  E:\Developer\dev-laravel\laravel8\laravel-indo\laravel8-shop\tests\Unit\Admin\CategoryRepositoryTest.php:19

//   Tests:  1 risked
//   Time:   78.93s

// k: assert : memastikan

// $this->assertInstanceOf(Category::class, $category);
// memastikan response nya merupakan instance object dari class category

// k: memastikan semua field yang sesuai dengan yang di database
// $this->assertEquals($params['name'], $category->name);
// $this->assertEquals(Str::slug($params['name']), $category->slug);
// $this->assertEquals(0, $category->slug);

// * kesimpulan testcase  testCanCreateCategory(),
// disini kita sudah berhasil membuat 1 test case untuk yang positive
// kita bisa create category ketika kita mengirim parameter name
// jadi dengan parameter name dianggap sudah cukup untuk membuat sebuah category
