<?php

namespace App\Http\Controllers\API;

use App\Exceptions\OutOfStockException;
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
        $items = $this->cartRepository->getContent($this->getSessionKey($request));

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

        try{

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

        if ($this->cartRepository->addItem($item, $this->getSessionKey($request))) {
            return $this->responseOk(true, 200, 'success');
        };

        }catch(OutOfStockException $error){
            return $this->responseError($error->getMessage(), 400);
        }

        return $this->responseError('Add item failed');
    }

    public function update(Request $request, $cart_id)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'qty' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->responseError('Add item failed', 422, $validator->errors());
        }

        $cartItem = $this->cartRepository->getCartItem($cart_id, $this->getSessionKey($request));

        if(!$cartItem){
            return $this->responseError('Item not found', 404);
        }

        try {
            $this->catalogRepository->checkProductInventory($cartItem->associatedModel, $params['qty']);

            if($this->cartRepository->updateCart($cart_id, $params['qty'], $this->getSessionKey($request))){
                return $this->responseOk(true, 200, 'Item has been updated!');
            };

            return $this->responseError('Update item failed', 422);
        }catch(OutOfStockException $error){
            return $this->responseError($error->getMessage(), 400);
        }

        return $this->responseError('Update item failed', 422);
    }

    public function destroy(Request $request, $cart_id)
    {
        $cartItem = $this->cartRepository->getCartItem($cart_id, $this->getSessionKey($request));

        if (!$cartItem) {
            return $this->responseError('Item not found', 404);
        }

        if($this->cartRepository->removeItem($cart_id, $this->getSessionKey($request))){
            return $this->responseOk(true, 200, 'Item has been deleted');
        }

        return $this->responseError('Delete item failed', 400);
    }

    public function clear(Request $request)
    {
        if($this->cartRepository->clear($this->getSessionKey($request))){
            return $this->responseOk(true, 200, 'Item has been cleared');
        }

        return $this->responseError('Clear cart failed', 400);
    }

    private function getSessionKey($request)
    {
        return md5($request->user()->id);
    }
}









// ketika kita pindah endpoint (setelah menyimpan cart dan kemudian list cart)
// list cart (method index) tidak terdapat data nya (ini masalah session)
// oleh karena itu kita perlu tambahkan user_id sebagai session key nya
