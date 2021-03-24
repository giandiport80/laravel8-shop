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
     * @param  mixed $sessionKey
     * @return void
     */
    public function isEmpty($sessionKey = null);

    /**
     * removeConditionsByType
     *
     * @param  mixed $type
     * @param  mixed $sessionKey
     * @return void
     */
    public function removeConditionsByType($type, $sessionKey = null);

    /**
     * updateTax
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function updateTax($sessionKey = null);

    /**
     * getTotalWeight
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getTotalWeight($sessionKey = null);

    /**
     * getBaseTotalPrice
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getBaseTotalPrice($sessionKey = null);

    /**
     * getTotal
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getTotal($sessionKey = null);

    /**
     * getTotalQuantity
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getTotalQuantity($sessionKey = null);

    /**
     * getSubTotal
     *
     * @param  mixed $sessionKey
     * @return void
     */
    public function getSubTotal($sessionKey = null);

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

    /**
     * getConditionValue
     *
     * @param  mixed $name
     * @param  mixed $sessionKey
     * @return void
     */
    public function getConditionValue($name, $sessionKey = null);

    /**
     * getCondition
     *
     * @param  mixed $name
     * @param  mixed $sessionKey
     * @return void
     */
    public function getCondition($name, $sessionKey = null);
}
