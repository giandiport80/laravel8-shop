<?php

namespace App\Repositories\Admin;

use App\Models\AttributeOption;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    private $_attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->_attributeRepository = $attributeRepository;
    }

    /**
     * paginate
     *
     * @param  mixed $perPage
     * @return void
     */
    public function paginate(int $perPage)
    {
        return Product::orderBy('name')->paginate($perPage);
    }

    /**
     * create
     *
     * @param  mixed $params
     * @return void
     */
    public function create($params = [])
    {
        $params['slug'] = Str::slug($params['name']);

        $product = DB::transaction(function () use ($params) {
            $categoryIds = !empty($params['category_ids']) ? $params['category_ids'] : [];
            $product = Product::create($params);
            $product->categories()->sync($categoryIds);

            if ($params['type'] == 'configurable') {
                $this->generateProductVariants($product, $params);
            }

            return $product;
        });

        return $product;
    }

    /**
     * update
     *
     * @param  mixed $params
     * @param  mixed $product
     * @return void
     */
    public function update($params, Product $product)
    {
        $params['slug'] = Str::slug($params['name']);

        $saved = false;
        $saved = DB::transaction(function () use ($product, $params) {
            $categoryIds = !empty($params['category_ids']) ? $params['category_ids'] : [];
            $product->update($params);
            $product->categories()->sync($categoryIds);

            if ($product->type == 'configurable') {
                $this->updateProductVariants($params);
            } else {
                ProductInventory::updateOrCreate(
                    ['product_id' => $product->id],
                    ['qty' => $params['qty']]
                );
            }

            return true;
        });

        return $saved;
    }

    /**
     * findById
     *
     * @param  mixed $id
     * @return void
     */
    public function findById(int $id)
    {
        return Product::findOrFail($id);
    }

    /**
     * addImage
     *
     * @param  mixed $id
     * @param  mixed $image
     * @return void
     */
    public function addImage(int $id, $image)
    {
        $product = Product::findOrFail($id);

        $name = $product->slug . '_' . time();
        $fileName = $name . '.' . $image->getClientOriginalExtension();
        $folder = 'uploads/images';
        $filePath = $image->storeAs($folder, $fileName, 'public');

        $params = [
            'product_id' => $product->id,
            'path' => $filePath
        ];

        return ProductImage::create($params);
    }

    /**
     * removeImage
     *
     * @param  mixed $id
     * @return void
     */
    public function removeImage(int $id)
    {
        $image = $this->findImageById($id);

        return $image->delete();
    }

    /**
     * findImageById
     *
     * @param  mixed $id
     * @return void
     */
    public function findImageById(int $id)
    {
        return ProductImage::findOrFail($id);
    }

    /**
     * statuses
     *
     * @return void
     */
    public function statuses()
    {
        return Product::statuses();
    }

    /**
     * types
     *
     * @return void
     */
    public function types()
    {
        return Product::types();
    }

    /**
     * delete
     *
     * @param  mixed $product
     * @return void
     */
    public function delete(Product $product)
    {
        if ($product->variants) {
            foreach ($product->variants as $variant) {
                $variant->delete();
            }
        }

        return $product->delete();
    }

    /**
     * updateProductVariants
     * for method update()
     *
     * @param  mixed $params
     * @return void
     */
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

    /**
     * generateProductVariants
     * for method store()
     *
     * @param  mixed $product
     * @param  mixed $params
     * @return void
     */
    private function generateProductVariants($product, $params) // .. 2
    {
        $configurableAttributes =  $this->_attributeRepository->getConfigurableAttributes();
        $variantAttributes = [];

        foreach ($configurableAttributes as $attribute) {
            $variantAttributes[$attribute->code] = $params[$attribute->code];
        }

        // dd($variantAttributes);

        $variants = $this->generateAttributeCombinations($variantAttributes); // .. 3

        // echo '<pre>';
        // print_r($variants);
        // exit;

        if ($variants) {
            foreach ($variants as $variant) {
                $variantParams = [
                    'parent_id' => $product->id,
                    'user_id' => $params['user_id'],
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

    /**
     * generateAttributeCombinations
     * for method store()
     *
     * @param  mixed $arrays
     * @return void
     */
    private function generateAttributeCombinations($arrays) // .. 4
    {
        $result = [[]];

        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }

        return $result;
    }

    /**
     * convertVariantAsName
     * for method store()
     *
     * @param  mixed $variant
     * @return void
     */
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

    /**
     * saveProductAttributeValues
     * for method store()
     *
     * @param  mixed $product
     * @param  mixed $variant
     * @return void
     */
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
}
