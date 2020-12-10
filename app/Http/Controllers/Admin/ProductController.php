<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use Authorizable; // .. 6

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'catalog';
        $this->data['currentAdminSubMenu'] = 'product';
        
        $this->data['statuses'] = Product::statuses();
        $this->data['types'] = Product::types();
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
        $configurableAttributes = $this->getConfigurableAttributes();

        $this->data['categories'] = $categories->toArray();
        $this->data['product'] = null;
        $this->data['productID'] = 0;
        $this->data['categoryIDs'] = [];
        $this->data['configurableAttributes'] = $configurableAttributes;

        return view('admin.products.form', $this->data);
    }

    // ! private method
    // ! ============================================================================

    private function getConfigurableAttributes() // .. 1
    {
        return Attribute::where('is_configurable', true)->get();
    }

    private function generateAttributeCombinations($arrays) // .. 4
    {
        $result = [[]];

        foreach($arrays as $property => $property_values){
            $tmp = [];
            foreach($result as $result_item){
                foreach($property_values as $property_value){
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }

        return $result;
    }

    private function convertVariantAsName($variant)
    {
        $variantName = '';

        foreach (array_keys($variant) as $key => $code) {
            $attributeOptionID = $variant[$code];
            $attributeOption = AttributeOption::find($attributeOptionID);

            if ($attributeOption) {
                $variantName .= ' - ' . $attributeOption->name;
            }
        }

        return $variantName;
    }

    private function generateProductVariants($product, $params) // .. 2
    {
        $configurableAttributes = $this->getConfigurableAttributes();
        $variantAttributes = [];

        foreach($configurableAttributes as $attribute){
            $variantAttributes[$attribute->code] = $params[$attribute->code];
        }

        // dd($variantAttributes);

        $variants = $this->generateAttributeCombinations($variantAttributes); // .. 3

        // echo '<pre>';
        // print_r($variants);
        // exit;

        if($variants){
            foreach($variants as $variant){
                $variantParams = [
                    'parent_id' => $product->id,
                    'user_id' => auth()->id(),
                    'sku' => $product->sku . '-' . implode('-', array_values($variant)),
                    'type' => 'simple',
                    'name' => $product->name . $this->convertVariantAsName($variant),
                ];

                $variantParams['slug'] = Str::slug($variantParams['name']);

                $newProductVariant = Product::create($variantParams);

                $categoryIds = !empty($params['category_ids']) ? $params['category_ids'] : [];
                $newProductVariant->categories()->sync($categoryIds);

                // dd($variantParams);

                $this->saveProductAttributeValues($newProductVariant, $variant);
            }
        }
    }

    private function saveProductAttributeValues($product, $variant) // ..
    {
        foreach (array_values($variant) as $attributeOptionID) {
            $attributeOption = AttributeOption::find($attributeOptionID);

            $attributeValueParams = [
                'product_id' => $product->id,
                'attribute_id' => $attributeOption->attribute_id,
                'text_value' => $attributeOption->name,
            ];

            ProductAttributeValue::create($attributeValueParams);
        }
    }


    // ! ============================================================================

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

        $product = DB::transaction(function () use ($params) {
            $categoryIds = !empty($params['category_ids']) ? $params['category_ids'] : [];
            $product = Product::create($params);
            $product->categories()->sync($categoryIds);

            if($params['type'] == 'configurable'){
                $this->generateProductVariants($product, $params);
            }

            return $product;
        });

        if($product){
            session()->flash('success', 'Product has been saved!');
        }else {
            session()->flash('error', 'Product could not be saved!');
        }

        return redirect()->route('products.edit', $product->id);
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
        $product->qty = isset($product->ProductInventory) ? $product->productInventory->qty : null;
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
            $categoryIds = !empty($params['category_ids']) ? $params['category_ids'] : [];
            $product->update($params);
            $product->categories()->sync($categoryIds);

            if($product->type == 'configurable'){
                $this->updateProductVariants($params);
            }else {
                ProductInventory::updateOrCreate(
                    ['product_id' => $product->id],
                    ['qty' => $params['qty']]
                );
            }

            return true;
        });

        if ($saved) {
            session()->flash('success', 'Product has been updated!');
        } else {
            session()->flash('error', 'Product could not be updated!');
        }

        return redirect()->route('products.index');
    }

    // ! method private
    // ! ============================================================================

    private function updateProductVariants($params)
    {
        if ($params['variants']) {
            foreach ($params['variants'] as $productParams) {
                $product = Product::find($productParams['id']);
                $product->update($productParams);

                $product->status = $params['status'];
                $product->save();

                ProductInventory::updateOrCreate(['product_id' => $product->id], ['qty' => $productParams['qty']]);
            }
        }
    }

    // ! ============================================================================


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

        if($image->path){
            Storage::delete('public/' . $image->path);
        }

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

// p: clue 1
// query attribute yang configurable nya true

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
