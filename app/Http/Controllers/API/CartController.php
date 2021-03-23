<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ItemResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{
    private $cartRepository, $catalogRepository;

    public function __construct(CartRepositoryInterface $cartRepository, CatalogRepositoryInterface $catalogRepository)
    {
        parent::__construct();

        $this->cartRepository = $cartRepository;
        $this->catalogRepository = $catalogRepository;
    }

    public function index(Request $request)
    {
        $items = $this->cartRepository->getContent(md5($request->user()->id));

        return $this->responseOk(ItemResource::collection($items));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => ['required', 'string'],
            'qty' => ['required', 'numeric'],
            'size' => ['nullable', 'string'],
            'color' => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return $this->responseError('Add item failed', 422, $validator->errors());
        }

        $params = $request->all();

        $product = $this->catalogRepository->findProductBySku($params['sku']);

        $attribute = [];

        if ($product->configurable()) {
            $product = $this->catalogRepository->getProductByAttributes($product, $params);

            $attribute['size'] = $params['size'];
            $attribute['color'] = $params['color'];
        }

        $itemQuantity = $this->cartRepository->getItemQuantity($product->id, $params['qty']);

        $this->catalogRepository->checkProductInventory($product, $itemQuantity);

        $item = [
            'id' => md5($product->id),
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $params['qty'],
            'attributes' => $attribute,
            'associatedModel' => $product
        ];

        if ($this->cartRepository->addItem($item, md5($request->user()->id))) {
            return $this->responseOk(true, 200, 'Success');
        };

        return $this->responseError('Add item failed');
    }
}









// ketika kita pindah endpoint (setelah menyimpan cart dan kemudian list cart)
// list cart (method index) tidak terdapat data nya (ini masalah session)
// oleh karena itu kita perlu tambahkan user_id sebagai session key nya
