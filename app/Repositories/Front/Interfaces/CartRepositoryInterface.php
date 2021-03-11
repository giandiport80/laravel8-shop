<?php

namespace App\Repositories\Front\Interfaces;

interface CartRepositoryInterface
{
    /**
     * getContent
     *
     * @return void
     */
    public function getContent();

    /**
     * getItemQuantity
     *
     * @param  mixed $productId
     * @param  mixed $qtyRequested
     * @return void
     */
    public function getItemQuantity($productId, $qtyRequested);

    /**
     * getCartItem
     *
     * @param  mixed $cartId
     * @return void
     */
    public function getCartItem($cartId);

    /**
     * addItem
     *
     * @param  mixed $item
     * @return void
     */
    public function addItem($item);

    /**
     * updateCart
     *
     * @param  mixed $cartId
     * @param  mixed $qty
     * @return void
     */
    public function updateCart($cartId, $qty);


    /**
     * removeItem
     *
     * @param  mixed $id
     * @return void
     */
    public function removeItem($cartId);
}
