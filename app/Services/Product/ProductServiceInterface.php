<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\ServiceInterface;

interface ProductServiceInterface extends ServiceInterface {
    public function getRalatedProducts($product,$limit=4);
    public function getFeaturedProducts();
    public function getProductOnIndex($request);
    public function getProductsByCategory($categoryName,$request);
}

