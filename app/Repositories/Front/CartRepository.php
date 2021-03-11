<?php

namespace App\Repositories\Front;

use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use Darryldecode\Cart\Facades\CartFacade;

class CartRepository implements CartRepositoryInterface
{
    /**
     * getContent
     * mengambil semua item product
     *
     * @return void
     */
    public function getContent()
    {
        return CartFacade::getContent();
    }

    /**
     * getItemQuantity
     *
     * @param  mixed $productId
     * @param  mixed $qtyRequested
     * @return void
     */
    public function getItemQuantity($productId, $qtyRequested)
    {
        return $this->_getItemQuantity(md5($productId)) + $qtyRequested;
    }

    /**
     * getCartItem
     *
     * @param  mixed $cartId
     * @return void
     */
    public function getCartItem($cartId)
    {
        $items = CartFacade::getContent();

        return $items[$cartId];
    }

    /**
     * addItem
     *
     * @param  mixed $item
     * @return void
     */
    public function addItem($item)
    {
        return CartFacade::add($item); // .. 5
    }

    /**
     * updateCart
     *
     * @param  mixed $cartId
     * @param  mixed $qty
     * @return void
     */
    public function updateCart($cartId, $qty)
    {
        return CartFacade::update($cartId, [ // .. 6
            'quantity' => [
                'relative' => false,
                'value' => $qty
            ]
        ]);
    }

    /**
     * removeItem
     *
     * @param  mixed $id
     * @return void
     */
    public function removeItem($cartTd)
    {
        return CartFacade::remove($cartTd);
    }

    // k: ==================== private method ====================

    /**
     * _getItemQuantity
     *
     * @param  mixed $itemId
     * @return void
     */
    private function _getItemQuantity($itemId)
    {
        $items = CartFacade::getContent();
        $itemQuantity = 0;
        if ($items) {
            foreach ($items as $item) {
                if ($item->id == $itemId) {
                    $itemQuantity = $item->quantity;
                    break;
                }
            }
        }

        return $itemQuantity;
    }
}









// h: DOKUMENTASI

// p: clue 5
// CartFacade::add($item);
// menambahkan item yang sudah kita siapkan ke keranjang
