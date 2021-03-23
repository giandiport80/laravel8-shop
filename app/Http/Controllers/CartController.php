<?php

namespace App\Http\Controllers;

use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cartRepository, $catalogRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CatalogRepositoryInterface $catalogRepository
    ) {
        parent::__construct();

        $this->cartRepository = $cartRepository;
        $this->catalogRepository = $catalogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['items'] = $this->cartRepository->getContent();

        return $this->load_theme('carts.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->except('_token');
        $product = $this->catalogRepository->findByProductId($params['product_id']);
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

        $this->cartRepository->addItem($item);

        session()->flash('success', 'Product' . $item['name'] . 'has been been added to cart!');

        return redirect('product/' . $product->slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $params = $request->except('_token');

        if ($items = $params['items']) {
            foreach ($items as $cartID => $item) {
                $cartItem = $this->cartRepository->getCartItem($cartID);
                $this->catalogRepository->checkProductInventory($cartItem->associatedModel, $item['quantity']);

                $this->cartRepository->updateCart($cartID, $item['quantity']);
            }
        }

        session()->flash('success', 'The cart has been updated!');

        return redirect()->route('carts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRepository->removeItem($id);

        return redirect()->route('carts.index');
    }
}










// h: DOKUMENTASI

// k: pada carts/index.blade.php
// Cart::getSubTotal()
// mendapatkan seluruh total harga product di keranjang tanpa pajak / diskon

// Cart::getSubTotal()
// mendapatkan seluruh total harga product di keranjang

// k: pada partials/mini_cart.blade.php
// Cart::getTotalQuantity()
// mendapatkan total quantity

// Cart::getCondition('TAX 10%')->
// disitu kita menambahkan kondisi (pajak, ongkos kirim, dan lain lain)
// dalam hal ini kita menambahkan pajak 10%
