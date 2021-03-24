<?php

namespace App\Repositories\Front\Interfaces;

interface OrderRepositoryInterface
{
    /**
     * getOrders
     *
     * @param  mixed $user
     * @param  mixed $perPage
     * @return void
     */
    public function getOrders($user, $perPage);

    /**
     * getOrder
     *
     * @param  mixed $user
     * @param  mixed $orderId
     * @return void
     */
    public function getOrder($user, $orderId);

    /**
     * saveOrder
     *
     * @param  mixed $params
     * @param  mixed $sessionKey
     * @return void
     */
    public function saveOrder($params, $sessionKey = null);
}
