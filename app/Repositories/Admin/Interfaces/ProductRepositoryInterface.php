<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function paginate(int $perPage);

    public function create($params = []);

    public function findById(int $id);

    public function update($params, Product $product);

    public function delete(Product $product);

    public function addImage(int $id, $image);

    public function findImageById(int $id);

    public function removeImage(int $id);

    public function types();

    public function statuses();
}
