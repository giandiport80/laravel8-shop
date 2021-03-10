<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductRequest;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use Authorizable; // .. 6

    private $_productRepository, $_categoryRepository, $_attributeRepository;

    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, AttributeRepositoryInterface $attributeRepository)
    {
        parent::__construct();

        $this->_productRepository = $productRepository;
        $this->_categoryRepository = $categoryRepository;
        $this->_attributeRepository = $attributeRepository;

        $this->data['currentAdminMenu'] = 'catalog';
        $this->data['currentAdminSubMenu'] = 'product';

        $this->data['statuses'] = $this->_productRepository->statuses();
        $this->data['types'] = $this->_productRepository->types();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['products'] = $this->_productRepository->paginate(10);

        return view('admin.products.index', $this->data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['categories'] = $this->_categoryRepository->getCategoryDropDown();
        $this->data['product'] = null;
        $this->data['productID'] = 0;
        $this->data['categoryIDs'] = [];
        $this->data['configurableAttributes'] = $this->_attributeRepository->getConfigurableAttributes();

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
        $params['user_id'] = Auth::id();

        if ($product = $this->_productRepository->create($params)) {
            session()->flash('success', 'Product has been saved!');
            return redirect()->route('products.edit', $product->id);
        } else {
            session()->flash('error', 'Product could not be saved!');
        }
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
        if (empty($id)) {
            return redirect()->route('products.create');
        }

        $product = $this->_productRepository->findById($id);
        $product->qty = isset($product->ProductInventory) ? $product->productInventory->qty : null;
        $categories = Category::orderBy('name')->get();

        $this->data['categories'] = $this->_categoryRepository->getCategoryDropDown();
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

        if ($this->_productRepository->update($params, $product)) {
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
        if ($this->_productRepository->delete($product)) {
            session()->flash('success', 'Product has been deleted!');
        }

        return redirect()->route('products.index');
    }

    // k: method2 product images
    // ============================================================================

    public function images($id)
    {
        if (empty($id)) {
            return redirect()->route('products.create');
        }

        $product = Product::findOrFail($id);

        $this->data['productID'] = $product->id;
        $this->data['productImages'] = $product->productImages;

        return view('admin.products.images', $this->data);
    }

    public function addImage($id)
    {
        if (empty($id)) {
            return redirect()->route('products.index');
        }

        $product = $this->_productRepository->findById($id);

        $this->data['productID'] = $id;
        $this->data['product'] = $product;

        return view('admin.products.image_form', $this->data);
    }

    public function uploadImage(ProductImageRequest $request, $id)
    {
        if ($request->has('image')) {
            $image = $request->file('image');

            if ($this->_productRepository->addImage($id, $image)) {
                session()->flash('success', 'Image has been uploaded!');
            } else {
                session()->flash('error', 'Image could not be uploaded!');
            }

            return redirect('admin/products/' . $id . '/images');
        }
    }

    /**
     * remove_image
     *
     * @param  mixed $id
     * @return void
     */
    public function removeImage($id)
    {
        $image = $this->_productRepository->findImageById($id);

        if ($this->_productRepository->removeImage($id)) {
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

// p: clue 2
// mendapatkan variasi dari product nya
// misal attribute color: merah dan hitam
// attribute size: S dan M

// p: clue 3
// dari variant product nya, kemudian kita kombinasikan
// misal berdasarkan contoh di clue 2
// kita dapatkan kombinasi:
// ada 4 variant product yang terbuat
// - jersey MU warna merah size S
// - jersey MU warna merah size M
// - jersey MU warna hitam size S
// - jersey MU warna hitam size M

// Array
// (
//     [0] => Array
//         (
//             [color] => 1
//             [size] => 9
//         )

//     [1] => Array
//         (
//             [color] => 1
//             [size] => 10
//         )

//     [2] => Array
//         (
//             [color] => 5
//             [size] => 9
//         )

//     [3] => Array
//         (
//             [color] => 5
//             [size] => 10
//         )

// )

// p: clue 4
// $arrays adalah array dari attribute yang dipilih

// p: clue 5
// method yang akan menyimpan value-value dari attribute yang terpilih
// ke tb attributeValue

// p: clue 6
// trait Authorizable: melindungi user yang tidak memiliki permission tertentu
