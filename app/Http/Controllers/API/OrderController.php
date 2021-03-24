<?php

namespace App\Http\Controllers\API;

use App\Jobs\SendMailOrderReceived;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    private $cartRepository, $orderRepository;

    public function __construct(CartRepositoryInterface $cartRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
    }

    public function doCheckout(Request $request)
    {
        $params = $request->user()->toArray();
        $params = array_merge($params, $request->all());

        if($order = $this->orderRepository->saveOrder($params, $this->getSessionKey($request))){
            $this->cartRepository->clear($this->getSessionKey($request));
            $this->sendEmailOrderReceived($order);

            return $this->responseOk($order);
        }

        return $this->responseError('order process failed');
    }

    private function getSessionKey($request)
    {
        return md5($request->user()->id);
    }

    private function sendEmailOrderReceived($order)
    {
        SendMailOrderReceived::dispatch($order, Auth::user()); // .. 7
    }
}
