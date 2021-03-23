<?php

namespace App\Repositories\Front\Interfaces;

interface CartRepositoryInterface
{
    /**
     * getContent
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getContent($sessionKey = null);

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
     * @param  mixed $sessionKey
     * @return void
     */
    public function getCartItem($cartId, $sessionKey = null);

    /**
     * addItem
     *
     * @param  mixed $item
     * @param  mixed $sessionKey
     * @return void
     */
    public function addItem($item, $sessionKey = null);

    /**
     * updateCart
     *
     * @param  mixed $cartId
     * @param  mixed $qty
     * @param  mixed $sessionKey
     * @return void
     */
    public function updateCart($cartId, $qty, $sessionKey = null);

    /**
     * removeItem
     *
     * @param  mixed $cartId
     * @param  mixed $sessionKey
     * @return void
     */
    public function removeItem($cartId, $sessionKey = null);

    /**
     * isEmpty
     *
     * @return void
     */
    public function isEmpty();

    /**
     * removeConditionsByType
     *
     * @param  mixed $type
     * @return void
     */
    public function removeConditionsByType($type);

    /**
     * updateTax
     *
     * @return void
     */
    public function updateTax();

    /**
     * getTotalWeight
     *
     * @return void
     */
    public function getTotalWeight();

    /**
     * getTotal
     *
     * @return void
     */
    public function getTotal();

    /**
     * addShippingCostToCart
     *
     * @param  mixed $serviceName
     * @param  mixed $cost
     * @return void
     */
    public function addShippingCostToCart($serviceName, $cost);

    /**
     * getShippingCost
     *
     * @param  mixed $destination
     * @param  mixed $weight
     * @return void
     */
    public function getShippingCost($destination, $weight);

    /**
     * clear all cart
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function clear($sessionKey = null);
}
