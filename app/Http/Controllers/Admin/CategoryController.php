<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['categories'] = Category::orderBy('name')->paginate(10);

        return view('admin.categories.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $this->data['categories'] = $categories->toArray();
        $this->data['category'] = null;

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
        $params = $request->except('_token');

        $params['slug'] = Str::slug($params['name']);
        $params['parent_id'] = (int) $params['parent_id'];

        if(Category::create($params)){
            session()->flash('success', 'Category has been saved!');
        }

        return redirect()->route('categories.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::orderBy('name')->get();

        $this->data['categories'] = $categories->toArray();
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
        $params = $request->except('_token');

        $params['slug'] = Str::slug($params['name']);
        $params['parent_id'] = (int) $params['parent_id'];

        if($category->update($params)){
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
        if($category->delete()){
            session()->flash('success', 'Category has been deleted!');
        }

        return back();
    }
}










// h: DOKUMENTASI

// untuk sementara, parent_id kita buat 0
// karena kita belum support multilevel category
