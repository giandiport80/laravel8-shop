<?php

namespace App\Repositories\Front;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductInventory;
use App\Models\Shipment;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\OrderRepositoryInterface;
use Darryldecode\Cart\Facades\CartFacade;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderRepository implements OrderRepositoryInterface
{
    private $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * getOrders
     *
     * @param  mixed $user
     * @param  mixed $perPage
     * @return void
     */
    public function getOrders($user, $perPage)
    {
        return Order::forUser($user)
        ->orderBy('created_at', 'DESC')
        ->paginate($perPage);
    }

    /**
     * getOrder
     *
     * @param  mixed $user
     * @param  mixed $orderId
     * @return void
     */
    public function getOrder($user, $orderId)
    {
        return Order::forUser($user)->findOrFail($orderId);
    }

    /**
     * saveOrder
     *
     * @param  mixed $params
     * @param  mixed $sessionKey
     * @return void
     */
    public function saveOrder($params, $sessionKey = null)
    {
        return DB::transaction(function () use ($params, $sessionKey) {
            $order = $this->saveOrderData($params, $sessionKey);
            $this->_saveOrderItems($order, $sessionKey);
            $this->_generatePaymentToken($order);
            $this->_saveShipment($order, $params, $sessionKey);

            return $order;
        });
    }

    // .. ==================== private method ====================

    private function saveOrderData($params, $sessionKey)
    {
        $destination = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
        $selectedShipping = $this->getSelectedShipping($destination, $this->getTotalWeight($sessionKey), $params['shipping_service']);

        $baseTotalPrice = $this->cartRepository->getBaseTotalPrice($sessionKey);
        $this->cartRepository->getSubTotal($sessionKey);
        $taxAmount = $this->cartRepository->getCondition('TAX 10%', $sessionKey)->parsedRawValue;
        $taxPercent = (float) $this->cartRepository->getCondition('TAX 10%', $sessionKey)->getValue();
        $shippingCost = $selectedShipping['cost'];
        $discountAmount = 0;
        $discountPercent = 0;
        $grandTotal = ($baseTotalPrice + $taxAmount + $shippingCost) - $discountAmount;

        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

        $orderParams = [
            'user_id' => Auth::id(),
            'code' => Order::generateCode(),
            'status' => Order::CREATED,
            'order_date' => $orderDate,
            'payment_due' => $paymentDue,
            'payment_status' => Order::UNPAID,
            'base_total_price' => $baseTotalPrice,
            'tax_amount' => $taxAmount,
            'tax_percent' => $taxPercent,
            'discount_amount' => $discountAmount,
            'discount_percent' => $discountPercent,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'note' => $params['note'],
            'customer_first_name' => $params['first_name'],
            'customer_last_name' => $params['last_name'],
            'customer_company' => $params['company'],
            'customer_address1' => $params['address1'],
            'customer_address2' => $params['address2'],
            'customer_phone' => $params['phone'],
            'customer_email' => $params['email'],
            'customer_city_id' => $params['city_id'],
            'customer_province_id' => $params['province_id'],
            'customer_postcode' => $params['postcode'],
            'shipping_courier' => $selectedShipping['courier'],
            'shipping_service_name' => $selectedShipping['service'],
        ];

        return Order::create($orderParams);
    }

    /**
     * Get selected shipping from user input
     *
     * @param int    $destination     destination city
     * @param int    $totalWeight     total weight
     * @param string $shippingService service name
     *
     * @return array
     */
    private function getSelectedShipping($destination, $totalWeight, $shippingService)
    {
        $shippingOptions = $this->cartRepository->getShippingCost($destination, $totalWeight);

        $selectedShipping = null;
        if ($shippingOptions['results']) {
            foreach ($shippingOptions['results'] as $shippingOption) {
                if (str_replace(' ', '', $shippingOption['service']) == $shippingService) {
                    $selectedShipping = $shippingOption;
                    break;
                }
            }
        }

        return $selectedShipping;
    }

    private function getTotalWeight($sessionKey = null)
    {
        return $this->cartRepository->getTotalWeight($sessionKey);
    }

    private function _saveOrderItems($order, $sessionKey = null)
    {
        $cartItems = $this->cartRepository->getContent($sessionKey);

        if ($order && $cartItems) {
            foreach ($cartItems as $item) {
                $itemTaxAmount = 0;
                $itemTaxPercent = 0;
                $itemDiscountAmount = 0;
                $itemDiscountPercent = 0;
                $itemBaseTotal = $item->quantity * $item->price;
                $itemSubTotal = $itemBaseTotal + $itemTaxAmount - $itemDiscountAmount;

                $product = isset($item->associatedModel->parent) ? $item->associatedModel->parent : $item->associatedModel;

                $orderItemParams = [
                    'order_id' => $order->id,
                    'product_id' => $item->associatedModel->id,
                    'qty' => $item->quantity,
                    'base_price' => $item->price,
                    'base_total' => $itemBaseTotal,
                    'tax_amount' => $itemTaxAmount,
                    'tax_percent' => $itemTaxPercent,
                    'discount_amount' => $itemDiscountAmount,
                    'discount_percent' => $itemDiscountPercent,
                    'sub_total' => $itemSubTotal,
                    'sku' => $item->associatedModel->sku,
                    'type' => $product->type,
                    'name' => $item->name,
                    'weight' => $item->associatedModel->weight,
                    'attributes' => json_encode($item->attributes),
                ];

                // dd($orderItemParams);

                $orderItem = OrderItem::create($orderItemParams);

                // dd($orderItem->toArray());

                if ($orderItem) {
                    ProductInventory::reduceStock($orderItem->product_id, $orderItem->qty);
                }
            }
        }
    }

    private function _generatePaymentToken($order)
    {
        $this->initPaymentGateway();

        $customerDetails = [
            'first_name' => $order->customer_first_name,
            'last_name' => $order->customer_last_name,
            'email' => Auth::user()->email,
            'phone' => $order->customer_phone,
        ];

        $params = [
            'enabled_payments' => Payment::PAYMENT_CHANNELS,
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => $order->grand_total
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => Payment::EXPIRY_UNIT,
                'duration' => Payment::EXPIRY_DURATION
            ]
        ];

        // dd($params);
        // generate token midtrans
        $snap = Snap::createTransaction($params);

        if ($snap->token) {
            $order->payment_token = $snap->token;
            $order->payment_url = $snap->redirect_url;
            $order->save();
        }
    }

    private function _saveShipment($order, $params, $sessionKey = null)
    {
        $shippingFirstName = isset($params['ship_to']) ? $params['shipping_first_name'] : $params['first_name'];
        $shippingLastName = isset($params['ship_to']) ? $params['shipping_last_name'] : $params['last_name'];
        $shippingCompany = isset($params['ship_to']) ? $params['shipping_company'] : $params['company'];
        $shippingAddress1 = isset($params['ship_to']) ? $params['shipping_address1'] : $params['address1'];
        $shippingAddress2 = isset($params['ship_to']) ? $params['shipping_address2'] : $params['address2'];
        $shippingPhone = isset($params['ship_to']) ? $params['shipping_phone'] : $params['phone'];
        $shippingEmail = isset($params['ship_to']) ? $params['shipping_email'] : $params['email'];
        $shippingCityId = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
        $shippingProvinceId = isset($params['ship_to']) ? $params['shipping_province_id'] : $params['province_id'];
        $shippingPostcode = isset($params['ship_to']) ? $params['shipping_postcode'] : $params['postcode'];

        $shipmentParams = [
            'user_id' => Auth::user()->id,
            'order_id' => $order->id,
            'status' => Shipment::PENDING,
            'total_qty' => $this->cartRepository->getTotalQuantity($sessionKey),
            'total_weight' => $this->getTotalWeight($sessionKey),
            'first_name' => $shippingFirstName,
            'last_name' => $shippingLastName,
            'address1' => $shippingAddress1,
            'address2' => $shippingAddress2,
            'phone' => $shippingPhone,
            'email' => $shippingEmail,
            'city_id' => $shippingCityId,
            'province_id' => $shippingProvinceId,
            'postcode' => $shippingPostcode,
        ];

        // dd($shipmentParams); // .. 4

        return Shipment::create($shipmentParams);
    }

    private function initPaymentGateway()
    {
        // Set your Merchant Server Key
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }


}









// h: DOKUMENTASI

// p: clue 4
// pada saat kita create di db:transaction
// meskipun sudah berhasil di create datanya
// tapi masih belum masuk ke database
// harus nya ketika transaksinya berjalan normal
// datanya belum masuk ke database

