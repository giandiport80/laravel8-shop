<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    private $catalogRepository, $perPage = 9;

    public function __construct(CatalogRepositoryInterface $catalogRepository)
    {
        $this->catalogRepository = $catalogRepository;
    }

    /**
     * index
     * get all produtcts
     *
     * @return void
     */
    public function index(Request $request)
    {
        if(($perPage = (int) $request->per_page) && (int) $request->per_page <= 20){
            $this->perPage = $perPage;
        }

        $products = $this->catalogRepository->paginate($this->perPage, $request);

        $meta = [
            'per_page' => $this->perPage,
            'current_page' => $products->currentPage(),
            'total_page' => $products->lastPage(),
        ];

        return $this->responseOk(new ProductCollection($products), 200, 'Success', $meta);
    }


    /**
     * show
     *
     * @param  mixed $sku
     * @return void
     */
    public function show($sku)
    {
        $product = $this->catalogRepository->findProductBySku($sku);

        return $this->responseOk(ProductResource::collection($product));
    }

}










// kita bisa membuat token berdasarkan client credentianls token
// jika sebelumnya kita membuat token berdasarkan pada login
// jadi api producs.index adalah api yang boleh diakses tanpa harus login
// tapi harus ada autentikasi menggunakan token agar aman dengan client credentianls token

// > php artisan passport:client --client

// akan seperti ini
/*
$ php artisan passport:client --client

 What should we name the client? [LARAVEL8 SHOP ClientCredentials Grant Client]:
 > ClientApp

New client created successfully.
Client ID: 9302a7ac-a56b-4145-8761-ccc5bab2d52f
Client secret: GZR2S3sS5DXbBNfHAZYaHJDleIhFefffeeSNOg5Y
*/

// generate token untuk client / retrieving tokens

// kita harus merequest token ke http ke oauth/token
