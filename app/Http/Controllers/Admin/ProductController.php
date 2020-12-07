<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->data['statuses'] = Product::statuses();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['products'] = Product::orderBy('name')->paginate(10);

        return view('admin.products.index', $this->data);
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
        $this->data['product'] = null;
        $this->data['productID'] = 0;
        $this->data['categoryIDs'] = [];

        return view('admin.products.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $params = $request->except('_token');

        $params['slug'] = Str::slug($params['name']);
        $params['user_id'] = Auth::id();

        $saved = false;
        $saved = DB::transaction(function () use ($params) {
            $product = Product::create($params);
            $product->categories()->sync($params['category_ids']);

            return true;
        });

        if($saved){
            session()->flash('success', 'Product has been saved!');
        }else {
            session()->flash('error', 'Product could not be saved!');
        }

        return redirect()->route('products.index');
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
    public function edit($id)
    {
        if(empty($id)){
            return redirect()->route('products.create');
        }

        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        $this->data['categories'] = $categories->toArray();
        $this->data['product'] = $product;
        $this->data['productID'] = $product->id;
        $this->data['categoryIDs'] = $product->categories->pluck('id')->toArray();

        return view('admin.products.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $params = $request->except('_token');

        $params['slug'] = Str::slug($params['name']);

        $saved = false;
        $saved = DB::transaction(function () use ($product, $params) {
            $product->update($params);
            $product->categories()->sync($params['category_ids']);

            return true;
        });

        if ($saved) {
            session()->flash('success', 'Product has been updated!');
        } else {
            session()->flash('error', 'Product could not be updated!');
        }

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            session()->flash('success', 'Product has been deleted!');
        }

        return back();
    }

    // k: method2 product images
    // ============================================================================

    public function images($id)
    {
        if(empty($id)){
            return redirect()->route('products.create');
        }

        $product = Product::findOrFail($id);

        $this->data['productID'] = $product->id;
        $this->data['productImages'] = $product->productImages;

        return view('admin.products.images', $this->data);
    }

    public function add_image($id)
    {
        if(empty($id)){
            return redirect()->route('products.index');
        }

        $product = Product::findOrFail($id);

        $this->data['productID'] = $id;
        $this->data['product'] = $product;

        return view('admin.products.image_form', $this->data);
    }

    public function upload_image(ProductImageRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if($request->has('image')){
            $image = $request->file('image');
            $name = $product->slug . '_' . time();
            $fileName = $name . '.' . $image->getClientOriginalExtension();
            $folder = 'uploads/images';
            $filePath = $image->storeAs($folder, $fileName, 'public');

            $params = [
                'product_id' => $product->id,
                'path' => $filePath
            ];

            if(ProductImage::create($params)){
                session()->flash('success', 'Image has been uploaded!');
            }else{
                session()->flash('error', 'Image could not be uploaded!');
            }

            return redirect('admin/products/' . $id . '/images');
        }
    }

    public function remove_image($id)
    {
        $image = ProductImage::findOrFail($id);

        Storage::delete('public/' . $image->path);

        if ($image->delete()) {
            session()->flash('success', 'Image has been deleted!');
        }

        return redirect('admin/products/' . $image->product->id . '/images');
    }

    // ============================================================================

}










// h: DOKUMENTASI

// pada method destroy, kita tidak detach cateogory nya
// karena ini sudah otomatis, karena foreign key nya on delete cascade
// jadi ketika data product di hapus, maka foreign key nya juga dihapus
