<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\SendMailOrderReceived;
use App\Mail\OrderShipped;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductInventory;
use App\Models\Shipment;
use App\Models\User;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\CartCondition;
use Darryldecode\Cart\Facades\CartFacade;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::forUser(Auth::user())
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        $this->data['orders'] = $orders;

        return $this->load_theme('orders.index', $this->data);
    }

    public function show($id)
    {
        $order = Order::forUser(Auth::user())->findOrFail($id);
        $this->data['order'] = $order;

        return $this->load_theme('orders.show', $this->data);
    }

    public function checkout()
    {
        if(CartFacade::isEmpty()){
            return redirect('carts');
        }

        CartFacade::removeConditionsByType('shipping');

        $this->updateTax();

        $items = CartFacade::getContent();

        $this->data['items'] = $items;
        $this->data['totalWeight'] = $this->getTotalWeight() / 1000; // .. 1
        $this->data['user'] = Auth::user();
        $this->data['provinces'] = $this->getProvinces();
        $this->data['cities'] = isset(Auth::user()->province_id) ? $this->getCities(Auth::user()->province_id) : [];


        return $this->load_theme('orders.checkout', $this->data);
    }

    public function shippingCost(Request $request) // .. 3
    {

        $destination = $request->input('city_id');

        return $this->getShippingCost($destination, $this->getTotalWeight());
    }

    public function cities(Request $request)
    {
        $cities = $this->getCities($request->query('province_id'));

        return response()->json([
            'cities' => $cities
        ]);
    }

    public function setShipping(Request $request)
    {
        CartFacade::removeConditionsByType('shipping');

        $shippingService = $request->get('shipping_service');
        $destination = $request->get('city_id');

        $shippingOptions = $this->getShippingCost($destination, $this->getTotalWeight());

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

            $this->addShippingCostToCart($selectedShipping['service'], $selectedShipping['cost']);

            $data['total'] = number_format(CartFacade::getTotal());
        } else {
            $status = 400;
            $message = 'Failed to set shipping cost';
        }

        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return $response;
    }

    public function doCheckout(OrderRequest $request)
    {
        $params = $request->except('_token');

        $order = DB::transaction(function () use ($params) {
            $order = $this->_saveOrder($params);
            $this->_saveOrderItems($order);
            $this->_generatePaymentToken($order);
            $this->_saveShipment($order, $params);

            return $order;

        });

        if($order){
            CartFacade::clear();

            $this->_sendEmailOrderReceived($order); // .. 8

            session()->flash('success', 'Thank you, Your order has been received!');

            return redirect('orders/received/' . $order->id);
        }

        return redirect('orders/checkout');
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
        $snap = \Midtrans\Snap::createTransaction($params);

        if($snap->token){
            $order->payment_token = $snap->token;
            $order->payment_url = $snap->redirect_url;
            $order->save();
        }
    }

    private function _saveOrder($params)
    {
        $destination = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
        $selectedShipping = $this->getSelectedShipping($destination, $this->getTotalWeight(), $params['shipping_service']);

        $baseTotalPrice = CartFacade::getSubTotal();
        $taxAmount = CartFacade::getCondition('TAX 10%')->getCalculatedValue(CartFacade::getSubTotal());
        $taxPercent = (float) CartFacade::getCondition('TAX 10%')->getValue();
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

    private function _saveOrderItems($order)
    {
        $cartItems = CartFacade::getContent();

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

    private function _saveShipment($order, $params)
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
            'total_qty' => CartFacade::getTotalQuantity(),
            'total_weight' => $this->getTotalWeight(),
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

    public function received($orderID)
    {
        $this->data['order'] = Order::where('id', $orderID)->where('user_id', Auth::id())->firstOrFail();

        // $this->_sendEmailOrderReceived($this->data['order']); // .. 6
        return $this->load_theme('orders.received', $this->data);
    }



    // ! private method
    // ! ============================================================================

    private function _sendEmailOrderReceived($order)
    {
        // $message = new OrderShipped($order); // .. 5
        // Mail::to(Auth::user()->email)->send($message);

        SendMailOrderReceived::dispatch($order, Auth::user()); // .. 7
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
        $shippingOptions = $this->getShippingCost($destination, $totalWeight);

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

    private function addShippingCostToCart($serviceName, $cost)
    {
        $condition = new CartCondition([
            'name' => $serviceName,
            'type' => 'shipping',
            'target' => 'total',
            'value' => '+' . $cost
        ]);

        CartFacade::condition($condition);
    }

    private function getShippingCost($destination, $weight)
    {
        $params = [
            'origin' => env('RAJAONGKIR_ORIGIN'),
            'destination' => $destination,
            'weight' => $weight,
        ];

        $results = [];
        foreach ($this->couriers as $code => $courier) {
            $params['courier'] = $code;

            $response = $this->rajaOngkirRequest('cost', $params, 'POST');

            if (!empty($response['rajaongkir']['results'])) {
                foreach ($response['rajaongkir']['results'] as $cost) {
                    if (!empty($cost['costs'])) {
                        foreach ($cost['costs'] as $costDetail) {
                            $serviceName = strtoupper($cost['code']) . ' - ' . $costDetail['service'];
                            $costAmount = $costDetail['cost'][0]['value'];
                            $etd = $costDetail['cost'][0]['etd'];

                            $result = [
                                'service' => $serviceName,
                                'cost' => $costAmount,
                                'etd' => $etd,
                                'courier' => $code
                            ];

                            $results[] = $result;
                        }
                    }
                }
            }
        }

        $response = [
            'origin' => $params['origin'],
            'destination' => $destination,
            'weight' => $weight,
            'results' => $results,
        ];

        return $response;
    }

    private function getTotalWeight()
    {
        if(CartFacade::isEmpty()){
            return 0;
        }

        $totalWeight = 0;
        $items = CartFacade::getContent();

        foreach($items as $item){
            $totalWeight += ($item->quantity * $item->associatedModel->weight);
        }

        return $totalWeight;
    }

    private function updateTax()
    {
        CartFacade::removeConditionsByType('tax'); // .. 2

        $condition = new CartCondition([
            'name' => 'TAX 10%',
            'type' => 'tax',
            'target' => 'subtotal',
            'value' => '10%',
        ]);

        CartFacade::condition($condition);
    }

    // ! ============================================================================
}









// h: DOKUMENTASI

// p: clue 1
// weight pada product kita adalah gram
// kita bagi 1000 agar menjadi kg

// p: clue 2
// kita mengupdate tax nya
// sebelumnya kita menghapus semua kondisi dengan tipe tax / pajak
// agar ketika kita panggil method ini, cart condition nya tidak nambah / dari nol lagi

// p: clue 3
// menyiapkan biaya pengiriman
// parametere ke 1 $destination adalah kota tujuan
// parameter ke 2 adalah berat

// p: clue 4
// pada saat kita create di db:transaction
// meskipun sudah berhasil di create datanya
// tapi masih belum masuk ke database
// harus nya ketika transaksinya berjalan normal
// datanya belum masuk ke database

// k: order pertama diterima
// http://127.0.0.1:8000/orders/received/8

// p: clue 5
// kita kirim email berdasarkan class email OrderShipped yg kita buat

// * membuat queue
// ganti QUEUE_CONNECTION=database pada .env
// > php artisan queue:table

// > php artisan migrate

// * membuat job class
// > php artisan make:job SendMailOrderReceived

// p: clue 6
// setelah kita menggunakan job class
// kita tidak mengirimkan lewat method received
// tapi pada job method handle pada class SendMailOrderReceived yg kita buat

// p: clue 7
// kita jalankan job class nya
// kemudian akan create data queue di tb jobs nya
// untuk mengeksekusi queue nya kita gunakan
// > php artisan queue:listen
// pada cmd akan seperti ini
// [2020-12-19 15:51:12][1] Processing: App\Jobs\SendMailOrderReceived
// [2020-12-19 15:51:15][1] Processed:  App\Jobs\SendMailOrderReceived

// ketika job queue sukses, akan di hapus dari record
// dan email nya dikirim

// kita bisa tambahkan delay 1 menit
// SendMailOrderReceived::dispatch($order, Auth::user())->delay(now()->addMinutes(1));

// p: clue 8
// kita tempatkan di tempat yang pas
// setelah keranjang dihapus, kita kirim emailnya
