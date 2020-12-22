<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $products = Product::popular()->get();
        // $this->data['products'] = $products;

        $slides = Slide::active()->get();

        $this->data['slides'] = $slides;
        
        return $this->load_theme('home', $this->data);
    }
}
