<?php

namespace App\Repositories\Front\Interfaces;

interface CatalogRepositoryInterface
{
    /**
     * paginate
     *
     * @param  mixed $perPage
     * @return void
     */
    public function paginate(int $perPage, $request);

    /**
     * findBySlug
     *
     * @param  mixed $slug
     * @return void
     */
    public function findBySlug($slug);

    /**
     * getAttributeOptions
     *
     * @return void
     */
    public function getAttributeOptions($product, $attributeName);

    /**
     * getParentCategories
     *
     * @return void
     */
    public function getParentCategories();

    /**
     * getAttributeFilters
     *
     * @param  mixed $attributeName
     * @return void
     */
    public function getAttributeFilters($attributeName);

    /**
     * getMaxPrice
     *
     * @return void
     */
    public function getMaxPrice();

    /**
     * getMinPrice
     *
     * @return void
     */
    public function getMinPrice();
}
