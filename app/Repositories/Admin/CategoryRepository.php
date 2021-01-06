<?php

namespace App\Repositories\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function paginate(int $perPage)
    {
        // dd('test category repository');
        return Category::orderBy('name')->paginate($perPage);
    }

    public function findById(int $categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function getCategoryDropDown(?int $exceptCategoryId = null)
    {
        $categories = new Category();

        if($exceptCategoryId){
            $categories = $categories->where('id', '!=', $exceptCategoryId);
        }

        $categories = $categories->orderBy('name');

        return $categories->get();
    }

    public function create(CategoryRequest $request)
    {
        $params = $request->except('_token');
        $params['slug'] = Str::slug($params['name']);
        $params['parent_id'] = (int) $params['parent_id'];

        return Category::create($params);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $params = $request->except('_token');
        $params['slug'] = Str::slug($params['name']);
        $params['parent_id'] = (int) $params['parent_id'];

        return $category->update($params);
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
