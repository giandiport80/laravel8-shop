<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    use Authorizable;

    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct();

        $this->categoryRepository = $categoryRepository;
        $this->data['currentAdminMenu'] = 'catalog';
        $this->data['currentAdminSubMenu'] = 'category';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['categories'] = $this->categoryRepository->paginate(10);

        return view('admin.categories.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['categories'] = $this->categoryRepository->getCategoryDropDown();

        return view('admin.categories.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $params = $request->validated();

        if ($this->categoryRepository->create($params)) {
            session()->flash('success', 'Category has been saved!');
        }

        return redirect()->route('categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryRepository->getCategoryDropDown($category->id);

        $this->data['categories'] = $categories;
        $this->data['category'] = $category;

        return view('admin.categories.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $params = $request->validated();

        if ($this->categoryRepository->update($params, $category)) {
            session()->flash('success', 'Category has been updated!');
        }

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($this->categoryRepository->delete($category)) {
            session()->flash('success', 'Category has been deleted!');
        }

        return redirect()->route('categories.index');
    }
}










// h: DOKUMENTASI

// untuk sementara, parent_id kita buat 0
// karena kita belum support multilevel category
