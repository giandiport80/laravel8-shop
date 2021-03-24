<?php

namespace App\Http\Controllers;

use App\Repositories\Front\CartRepository;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $data = [];
    protected $uploadsFolder = 'uploads/';

    private $cartRepository;

    protected $provinces = [];

    public function __construct()
    {
        $this->initAdminMenu();
        $this->cartRepository = new CartRepository();
    }

    private function initAdminMenu()
    {
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentAdminSubMenu'] = '';
    }

    protected function load_theme($view, $data = [])
    {
        return view('themes/' . env('APP_THEME') . '/' . $view, $data);
    }

    protected function getProvinces()
    {
        $provinceFile = 'provinces.txt';
        $provinceFilePath = $this->uploadsFolder . 'files/' . $provinceFile;

        $isExistProvinceJson = Storage::disk('local')->exists($provinceFilePath);

        if (!$isExistProvinceJson) {
            $response = $this->cartRepository->rajaOngkirRequest('province');
            Storage::disk('local')->put($provinceFilePath, serialize($response['rajaongkir']['results']));
        }

        $province = unserialize(Storage::get($provinceFilePath));

        $provinces = [];
        if (!empty($province)) {
            foreach ($province as $province) {
                $provinces[$province['province_id']] = strtoupper($province['province']);
            }
        }

        return $provinces;
    }

    protected function getCities($provinceId)
    {
        $cityFile = 'cities_at_' . $provinceId . '.txt';
        $cityFilePath = $this->uploadsFolder . 'files/' . $cityFile;

        $isExistCitiesJson = Storage::disk('local')->exists($cityFilePath);

        if (!$isExistCitiesJson) {
            $response = $this->cartRepository->rajaOngkirRequest('city', ['province' => $provinceId]);
            Storage::disk('local')->put($cityFilePath, serialize($response['rajaongkir']['results']));
        }

        $cityList = unserialize(Storage::get($cityFilePath));

        $cities = [];
        if (!empty($cityList)) {
            foreach ($cityList as $city) {
                $cities[$city['city_id']] = strtoupper($city['type'] . ' ' . $city['city_name']);
            }
        }

        return $cities;
    }

    // private function rajaOngkirRequest($resource, $params = [], $method = 'GET')
    // {
    //     $client = new Client();

    //     $headers = ['key' => $this->rajaOngkirApiKey];
    //     $requestParams = [
    //         'headers' => $headers,
    //     ];

    //     $url = $this->rajaOngkirBaseUrl . $resource;
    //     if ($params && $method == 'POST') {
    //         $requestParams['form_params'] = $params;
    //     } else if ($params && $method == 'GET') {
    //         $query = is_array($params) ? '?' . http_build_query($params) : '';
    //         $url = $this->rajaOngkirBaseUrl . $resource . $query;
    //     }

    //     $response = $client->request($method, $url, $requestParams);

    //     return json_decode($response->getBody(), true);
    // }
}










// h: DOKUMENTASI
// $data
// variable yang nantinya akan kita isi dengan controller childnya

// load_theme()
// method untuk me load tema kita
// jadi kita tidak perlu menulis panjang view nya
