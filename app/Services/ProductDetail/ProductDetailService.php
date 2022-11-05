<?php

namespace App\Services\ProductDetail;

use App\Repositories\ProductDetail\ProductDetailRepositoryInterface;
use App\Services\BaseService;

class ProductDetailService extends BaseService implements ProductDetailServiceInterface
{
    public function __construct(ProductDetailRepositoryInterface $productDetailRepository){
        $this->repository = $productDetailRepository;
    }
}
