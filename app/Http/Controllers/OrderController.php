<?php

namespace App\Http\Controllers;

use App\Models\User;
use Darryldecode\Cart\CartCondition;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
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



    // ! private method
    // ! ============================================================================

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
