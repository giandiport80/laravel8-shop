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
     * @return void
     */
    public function saveOrder($params);
}
