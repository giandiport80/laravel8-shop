<?php

namespace App\Http\Controllers;

use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $catalogRepository;

    public function __construct(CatalogRepositoryInterface $catalogRepository)
    {
        parent::__construct();

        $this->catalogRepository = $catalogRepository;

        $this->data['q'] = null;

        $this->data['categories'] = $this->catalogRepository->getParentCategories();

        $this->data['minPrice'] = $this->catalogRepository->getMinPrice();
        $this->data['maxPrice'] = $this->catalogRepository->getMaxPrice();

        $this->data['colors'] = $this->catalogRepository->getAttributeFilters('color');

        $this->data['sizes'] = $this->catalogRepository->getAttributeFilters('size');

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
    public function index(Request $request)
    {
        $this->data['products'] = $this->catalogRepository->paginate(9, $request);

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
        $product = $this->catalogRepository->findBySlug($slug);

        if (!$product) {
            return redirect('products');
        }

        if ($product->type == 'configurable') {
            $this->data['colors'] = $this->catalogRepository->getAttributeOptions($product, 'color');
            $this->data['sizes'] = $this->catalogRepository->getAttributeOptions($product, 'size');
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
