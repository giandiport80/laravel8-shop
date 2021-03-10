<?php

namespace App\Repositories\Front;

use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;

use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Str;

class CatalogRepository implements CatalogRepositoryInterface
{
    /**
     * paginate
     *
     * @param  mixed $perPage
     * @return void
     */
    public function paginate(int $perPage, $request)
    {
        $products = Product::active();
        $products = $this->searchProducts($products, $request);
        $products = $this->filterProductByCategory($products, $request);
        $products = $this->filterProductByPriceRange($products, $request);
        $products = $this->filterProductByAttribute($products, $request);
        $products = $this->sortProducts($products, $request);

        return $this->data['products'] = $products->paginate($perPage);
    }

    /**
     * findBySlug
     *
     * @param  mixed $slug
     * @return Product
     */
    public function findBySlug($slug)
    {
        return Product::active()->where('slug', $slug)->firstOrFail();
    }

    /**
     * getAttributeOptions
     *
     * @param  mixed $attributeName
     * @return void
     */
    public function getAttributeOptions($product, $attributeName)
    {
        return ProductAttributeValue::getAttributeOptions($product, $attributeName)->pluck('text_value', 'text_value');
    }

    /**
     * getParentCategories
     *
     * @return void
     */
    public function getParentCategories()
    {
        return Category::parentCategories()->orderBy('name', 'asc')->get();
    }

    public function getAttributeFilters($attributeName)
    {
        return AttributeOption::whereHas('attribute', function ($query) use ($attributeName) {
            $query->where('code', $attributeName)
                ->where('is_filterable', 1);
        })->orderBy('name', 'asc')->get();
    }

    /**
     * getMaxPrice
     *
     * @return void
     */
    public function getMaxPrice()
    {
        return Product::max('price');
    }

    public function getMinPrice()
    {
        return Product::min('price');
    }







    /**
     * searchProducts
     * for method paginate()
     *
     * @param  mixed $products
     * @param  mixed $request
     * @return void
     */
    private function searchProducts($products, $request)
    {
        // fitur search
        if ($q = $request->query('q')) {
            $q = str_replace('-', ' ', Str::slug($q));

            $products = $products->whereRaw("MATCH(name, slug, short_description, description) AGAINST (? IN NATURAL LANGUAGE MODE)", [$q]);

            $this->data['q'] = $q;
        }

        return $products;
    }

    /**
     * filterProductByCategory
     * for method paginate()
     *
     * @param  mixed $products
     * @param  mixed $request
     * @return void
     */
    private function filterProductByCategory($products, $request)
    {
        // filter category
        if ($categorySlug = $request->query('category')) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $childIds = Category::childIds($category->id);
            $categoryIds = array_merge([$category->id], $childIds);

            $products = $products->whereHas('categories', function ($query) use ($categoryIds) { // .. 1
                $query->whereIn('category_id', $categoryIds);
            });
        }

        return $products;
    }

    /**
     * filterProductByPriceRange
     * for method paginate()
     *
     * @param  mixed $products
     * @param  mixed $request
     * @return void
     */
    private function filterProductByPriceRange($products, $request)
    {
        // filter price
        $lowPrice = null;
        $highPrice = null;

        if ($priceSlider = $request->query('price')) {
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

        return $products;
    }

    /**
     * filterProductByAttribute
     * for method paginate()
     *
     * @param  mixed $products
     * @param  mixed $request
     * @return void
     */
    private function filterProductByAttribute($products, $request)
    {
        // filter color
        if ($attributeOptionID = $request->query('option')) {
            $attributeOption = AttributeOption::findOrFail($attributeOptionID);

            $products = $products->whereHas('productAttributeValues', function ($query) use ($attributeOption) {
                $query->where('attribute_id', $attributeOption->attribute->id)
                    ->where('text_value', $attributeOption->name);
            });
        }

        return $products;
    }

    /**
     * sortProducts
     * for method paginate()
     *
     * @param  mixed $products
     * @param  mixed $request
     * @return void
     */
    private function sortProducts($products, $request)
    {
        // sorting
        if ($sort = preg_replace('/\s+/', '',  $request->query('sort'))) {
            $availableSorts = ['price', 'created_at'];
            $availableOrders = ['asc', 'desc'];
            $sortAndOrder = explode('-', $sort);

            $sortBy = strtolower($sortAndOrder[0]);
            $orderBy = strtolower($sortAndOrder[1]);

            // query sorting
            if (in_array($sortBy, $availableSorts) && in_array($orderBy, $availableOrders)) {
                $products = $products->orderBy($sortBy, $orderBy);
            }

            $this->data['selectedSort'] = url('products?sort=' . $sort);
        }

        return $products;
    }
}
