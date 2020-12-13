<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = CartFacade::getContent(); // .. 1

        $this->data['items'] = $items;

        return $this->load_theme('carts.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->except('_token');
        $product = Product::findOrFail($params['product_id']);
        $slug = $product->slug;
        $attribute = [];

        // var_dump($product->id); // .. 3
        // echo "<br>";

        if ($product->configurable()) {
            $product = Product::from('products as p')
                ->whereRaw("p.parent_id = :parent_product_id
							and (select pav.text_value
									from product_attribute_values pav
									join attributes a on a.id = pav.attribute_id
									where a.code = :size_code
									and pav.product_id = p.id
									limit 1
								) = :size_value
							and (select pav.text_value
									from product_attribute_values pav
									join attributes a on a.id = pav.attribute_id
									where a.code = :color_code
									and pav.product_id = p.id
									limit 1
								) = :color_value
								", [
                    'parent_product_id' => $product->id,
                    'size_code' => 'size',
                    'size_value' => $params['size'],
                    'color_code' => 'color',
                    'color_value' => $params['color'],
                ])->firstOrFail(); // .. 2

            // var_dump($product->id);exit; // .. 4

            $attribute['size'] = $params['size'];
            $attribute['color'] = $params['color'];
        }

        $item = [
            'id' => md5($product->id),
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $params['qty'],
            'attributes' => $attribute,
            'associatedModel' => $product
        ];

        CartFacade::add($item); // .. 5

        session()->flash('success', 'Product' . $item['name'] . 'has been been added to cart!');

        return redirect('product/' . $slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $params = $request->except('_token');

        if ($items = $params['items']) {
            foreach ($items as $cartID => $item) {
                CartFacade::update($cartID, [ // .. 6
                    'quantity' => [
                        'relative' => false,
                        'value' => $item['quantity']
                    ]
                ]);
            }
        }

        session()->flash('success', 'The cart has been updated!');

        return redirect()->route('carts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CartFacade::remove($id);

        return redirect()->route('carts.index');
    }
}










// h: DOKUMENTASI

// p: clue 1
// getContent()
// mengambil semua item product

// p: clue 2
// kita input product induknya
// karena configurable
// jadi kita harus mencari variant dari product yang sesuai dengan attribute yang dipilih
// kemudian dikirimkan ke product_attribute_values

// p: clue 3
// id product disini mengacu pada parent product configurable

// p: clue 4
// setelah proses query
// id product disini mengacu pada id product variant dari parent product

// p: clue 5
// CartFacade::add($item);
// menambahkan item yang sudah kita siapkan ke keranjang

// p: clue 6
// CartFacade::update()
// relative nya kita buat false (default nya true, menambah / incrementing)
// agar kita replace dengan value yang kita kirim

// k: pada carts/index.blade.php
// Cart::getSubTotal()
// mendapatkan subtotal harga product di keranjang

// Cart::getSubTotal()
// mendapatkan seluruh total harga product di keranjang

// k: pada partials/mini_cart.blade.php
// Cart::getTotalQuantity()
// mendapatkan total quantity


// k: pada orders/checkout.blade.php
// Cart::getCondition('TAX 10%')->
// disitu kita menambahkan kondisi (pajak, ongkos kirim, dan lain lain)
// dalam hal ini kita menambahkan pajak 10%
