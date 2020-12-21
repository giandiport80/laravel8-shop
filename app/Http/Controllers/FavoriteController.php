<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);

        $this->data['favorites'] = $favorites;

        return $this->load_theme('favorites.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'product_slug' => 'required',
            ]
        );

        $product = Product::where('slug', $request->get('product_slug'))->firstOrFail();

        $favorite = Favorite::where('user_id', Auth::user()->id)
        ->where('product_id', $product->id)
        ->first();
        if ($favorite) {
            return response('You have added this product to your favorite before', 422);
        }

        Favorite::create(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]
        );

        return response('The product has been added to your favorite', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);
        $favorite->delete();

        session()->flash('success', 'Your favorite has been removed');

        return redirect('favorites');
    }
}
