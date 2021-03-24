<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\SendMailOrderReceived;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $orderRepository, $cartRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $cartRepository
    ) {
        parent::__construct();

        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;

        $this->middleware('auth');
    }

    public function index()
    {
        $this->data['orders'] = $this->orderRepository->getOrders(Auth::user(), 10);

        return $this->load_theme('orders.index', $this->data);
    }

    public function show($id)
    {
        $order = $this->orderRepository->getOrder(Auth::user(), $id);
        $this->data['order'] = $order;

        return $this->load_theme('orders.show', $this->data);
    }

    public function checkout()
    {
        if ($this->cartRepository->isEmpty()) {
            return redirect('carts');
        }

        $this->cartRepository->removeConditionsByType('shipping');
        $this->cartRepository->updateTax();

        $items = $this->cartRepository->getContent();

        $this->data['items'] = $items;
        $this->data['totalWeight'] = $this->cartRepository->getTotalWeight() / 1000; // .. 1
        $this->data['user'] = Auth::user();
        $this->data['provinces'] = $this->getProvinces();
        $this->data['cities'] = isset(Auth::user()->province_id) ? $this->getCities(Auth::user()->province_id) : [];


        return $this->load_theme('orders.checkout', $this->data);
    }

    public function shippingCost(Request $request) // .. 3
    {
        $destination = $request->input('city_id');

        return $this->getShippingCost($destination, $this->cartRepository->getTotalWeight());
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
        $this->cartRepository->removeConditionsByType('shipping');

        $shippingService = $request->get('shipping_service');
        $destination = $request->get('city_id');

        $shippingOptions = $this->getShippingCost($destination, $this->cartRepository->getTotalWeight());

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

            $this->cartRepository->addShippingCostToCart($selectedShipping['service'], $selectedShipping['cost']);

            $data['total'] = number_format($this->cartRepository->getTotal());
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

        $order = $this->orderRepository->saveOrder($params);

        if ($order) {
            $this->cartRepository->clear();

            $this->_sendEmailOrderReceived($order); // .. 8

            session()->flash('success', 'Thank you, Your order has been received!');

            return redirect('orders/received/' . $order->id);
        }

        return redirect('orders/checkout');
    }

    public function received($orderID)
    {
        // $this->data['order'] = Order::where('id', $orderID)->where('user_id', Auth::id())->firstOrFail();
        $this->data['order'] = $this->orderRepository->getOrder(Auth::user(), $orderID);

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

    private function getShippingCost($destination, $weight)
    {
        return $this->cartRepository->getShippingCost($destination, $weight);
    }

    // ! ============================================================================
}









// h: DOKUMENTASI

// p: clue 1
// weight pada product kita adalah gram
// kita bagi 1000 agar menjadi kg

// p: clue 3
// menyiapkan biaya pengiriman
// parametere ke 1 $destination adalah kota tujuan
// parameter ke 2 adalah berat

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
