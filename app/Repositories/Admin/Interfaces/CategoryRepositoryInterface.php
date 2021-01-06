<?php

namespace App\Repositories\Admin\Interfaces;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function paginate(int $perPage);

    public function findById(int $categoryId);

    public function getCategoryDropDown(int $exceptCategoryId = null);

    public function create(CategoryRequest $request);

    public function update(CategoryRequest $request, Category $category);

    public function delete(Category $category);
}
