<?php

namespace App\Repositories\Admin;

use App\Exceptions\CategoryNotFoundErrorException;
use App\Exceptions\CreateCategoryErrorException;
use App\Exceptions\UpdateCategoryErrorException;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function paginate(int $perPage)
    {
        // dd('test category repository');
        return Category::orderBy('name')->paginate($perPage);
    }

    public function findById(int $categoryId)
    {
        try{
            return Category::findOrFail($categoryId);
        }catch(ModelNotFoundException $e){
            throw new CategoryNotFoundErrorException('Category not found guys');
        }
    }

    public function getCategoryDropDown(?int $exceptCategoryId = null)
    {
        $categories = new Category();

        if ($exceptCategoryId) {
            $categories = $categories->where('id', '!=', $exceptCategoryId);
        }

        $categories = $categories->orderBy('name');

        return $categories->get();
    }

    public function create($params)
    {
        $params['slug'] = isset($params['name']) ? Str::slug($params['name']) : null;

        if (!isset($params['parent_id'])) {
            $params['parent_id'] = 0;
        }

        try{
            return $this->model::create($params);
        }catch(QueryException $e){
            throw new CreateCategoryErrorException('Error on creating a category guys');
        }
    }

    public function update($params, Category $category)
    {
        $params['slug'] = isset($params['name']) ? Str::slug($params['name']) : null;

        if (!isset($params['parent_id'])) {
            $params['parent_id'] = 0;
        }

        try {
            return $category->update($params);
        } catch (QueryException $e) {
            throw new UpdateCategoryErrorException('error when update!');
        }
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
