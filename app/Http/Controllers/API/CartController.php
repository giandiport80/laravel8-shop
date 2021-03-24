<?php

namespace App\Http\Controllers\API;

use App\Exceptions\OutOfStockException;
use App\Http\Resources\ItemResource;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use GuzzleHttp\Exception\RequestException;
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

        $cart = [
            'items' => ItemResource::collection($items),
            'shipping_cost' => $this->cartRepository->getConditionValue('shipping_cost', $this->getSessionKey($request)),
            'tax_amount' => $this->cartRepository->getConditionValue('TAX 10%', $this->getSessionKey($request)),
            'total' => $this->cartRepository->getTotal($this->getSessionKey($request))
        ];

        return $this->responseOk($cart);
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

        try {

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
        } catch (OutOfStockException $error) {
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

        if (!$cartItem) {
            return $this->responseError('Item not found', 404);
        }

        try {
            $this->catalogRepository->checkProductInventory($cartItem->associatedModel, $params['qty']);

            if ($this->cartRepository->updateCart($cart_id, $params['qty'], $this->getSessionKey($request))) {
                return $this->responseOk(true, 200, 'Item has been updated!');
            };

            return $this->responseError('Update item failed', 422);
        } catch (OutOfStockException $error) {
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

        if ($this->cartRepository->removeItem($cart_id, $this->getSessionKey($request))) {
            return $this->responseOk(true, 200, 'Item has been deleted');
        }

        return $this->responseError('Delete item failed', 400);
    }

    public function clear(Request $request)
    {
        if ($this->cartRepository->clear($this->getSessionKey($request))) {
            return $this->responseOk(true, 200, 'Item has been cleared');
        }

        return $this->responseError('Clear cart failed', 400);
    }

    public function shippingOptions(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'city_id' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return $this->responseError('Get shipping options failed', 422, $validator->errors());
        }

        try {
            $destination = $params['city_id'];
            $weight = $this->cartRepository->getTotalWeight($this->getSessionKey($request));

            return $this->responseOk($this->cartRepository->getShippingCost($destination, $weight), 200, 'sucess');
        } catch (RequestException $error) {
            return $this->responseError($error->getMessage(), 400);
        }

        return $this->responseError('get shipping cost failed', 400);
    }

    public function setShipping(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'city_id' => ['required', 'numeric'],
            'shipping_service' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return $this->responseError('Set shipping failed', 422, $validator->errors());
        }

        $this->cartRepository->removeConditionsByType('shipping', $this->getSessionKey($request));

        $shippingService = $request->get('shipping_service');
        $destination = $request->get('city_id');

        $shippingOptions = $this->cartRepository->getShippingCost($destination, $this->cartRepository->getTotalWeight($this->getSessionKey($request)));

        $selectedShipping = null;
        if ($shippingOptions['results']) {
            foreach ($shippingOptions['results'] as $shippingOption) {
                if (str_replace(' ', '', $shippingOption['service']) == $shippingService) {
                    $selectedShipping = $shippingOption;
                    break;
                }
            }
        }

        $status = null;
        $message = null;
        $data = [];
        if ($selectedShipping) {
            $status = 200;
            $message = 'Success set shipping cost';

            $this->cartRepository->addShippingCostToCart('shipping_cost', $selectedShipping['cost']);

            $data['total'] = number_format($this->cartRepository->getTotal());

            return $this->responseOk($data, 200, 'success');
        }

        return $this->responseError('failed to set shipping cost', 400);
    }

    private function getSessionKey($request)
    {
        return md5($request->user()->id);
    }
}









// ketika kita pindah endpoint (setelah menyimpan cart dan kemudian list cart)
// list cart (method index) tidak terdapat data nya (ini masalah session)
// oleh karena itu kita perlu tambahkan user_id sebagai session key nya
