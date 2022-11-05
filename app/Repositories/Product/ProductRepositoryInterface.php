<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
   public function getRalatedProducts($product,$limit = 4);
   public function getFeaturedProductsByCategory($object);
   public function getProductOnIndex($request);
   public function getProductsByCategory($categoryName,$request);

}
