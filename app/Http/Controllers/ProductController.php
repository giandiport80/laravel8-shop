<?php

namespace App\Http\Controllers;

use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->data['q'] = null;

        $this->data['categories'] = Category::parentCategories()->orderBy('name', 'asc')->get();

        $this->data['minPrice'] = Product::min('price');
        $this->data['maxPrice'] = Product::max('price');

        $this->data['colors'] = AttributeOption::whereHas('attribute', function ($query) {
            $query->where('code', 'color')
                ->where('is_filterable', 1);
        })->orderBy('name', 'asc')->get();

        $this->data['sizes'] = AttributeOption::whereHas('attribute', function ($query) {
            $query->where('code', 'size')
                ->where('is_filterable', 1);
        })->orderBy('id', 'asc')->get();

        $this->data['sorts'] = [
            url('products') => 'Default',
            url('products?sort=price-asc') => 'Price - Low to High',
            url('products?sort=price-desc') => 'Price - High to Low',
            url('products?sort=created_at-desc') => 'Newest to Oldest',
            url('products?sort=created_at-asc') => 'Oldest to Newest',
        ];

        $this->data['selectedSort'] = url('products');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::active();

        // fitur search
        if ($q = request()->query('q')) {
            $q = str_replace('-', ' ', Str::slug($q));

            $products = $products->whereRaw("MATCH(name, slug, short_description, description) AGAINST (? IN NATURAL LANGUAGE MODE)", [$q]);

            $this->data['q'] = $q;
        }

        // filter category
        if ($categorySlug = request()->query('category')) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $childIds = Category::childIds($category->id);
            $categoryIds = array_merge([$category->id], $childIds);

            $products = $products->whereHas('categories', function ($query) use ($categoryIds) { // .. 1
                $query->whereIn('category_id', $categoryIds);
            });
        }

        // filter price
        $lowPrice = null;
        $highPrice = null;

        if ($priceSlider = request()->query('price')) {
            $prices = explode('-', $priceSlider);

            // convert result string to float
            $lowPrice = !empty($prices[0]) ? (float) $prices[0] : $this->data['minPrices'];
            $highPrice = !empty($prices[1]) ? (float) $prices[1] : $this->data['maxPrices'];

            if ($lowPrice && $highPrice) {
                $products = $products->where('price', '>=', $lowPrice)
                    ->where('price', '<=', $highPrice)
                    ->orWhereHas('variants', function ($query) use ($lowPrice, $highPrice) {
                        $query->where('price', '>=', $lowPrice)
                            ->where('price', '<=', $highPrice);
                    });

                $this->data['minPrice'] = $lowPrice;
                $this->data['maxPrice'] = $highPrice;
            }
        }

        // filter color
        if ($attributeOptionID = request()->query('option')) {
            $attributeOption = AttributeOption::findOrFail($attributeOptionID);

            $products = $products->whereHas('productAttributeValues', function ($query) use ($attributeOption) {
                $query->where('attribute_id', $attributeOption->attribute->id)
                    ->where('text_value', $attributeOption->name);
            });
        }

        // sorting
        if($sort = preg_replace('/\s+/', '',  request()->query('sort'))){
            $availableSorts = ['price', 'created_at'];
            $availableOrders = ['asc', 'desc'];
            $sortAndOrder = explode('-', $sort);

            $sortBy = strtolower($sortAndOrder[0]);
            $orderBy = strtolower($sortAndOrder[1]);

            // query sorting
            if(in_array($sortBy, $availableSorts) && in_array($orderBy, $availableOrders)){
                $products = $products->orderBy($sortBy, $orderBy);
            }

            $this->data['selectedSort'] = url('products?sort=' . $sort);
        }


        $this->data['products'] = $products->paginate(9);

        return $this->load_theme('products.index', $this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::active()->where('slug', $slug)->first();

        if (!$product) {
            return redirect('products');
        }

        if ($product->type == 'configurable') {
            $this->data['colors'] = ProductAttributeValue::getAttributeOptions($product, 'color')->pluck('text_value', 'text_value');
            $this->data['sizes'] = ProductAttributeValue::getAttributeOptions($product, 'size')->pluck('text_value', 'text_value');
        }

        $this->data['product'] = $product;

        return $this->load_theme('products.show', $this->data);
    }
}









// h: DOKUMENTASI

// p: clue 1
// whereHas()
// method untuk mencari kolom dari relasinya

// fungsi preg_replace()
// dalam kasus ini digunakan untuk menghilangkan spasi
