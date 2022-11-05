<?php

namespace App\Services\Brand;


use App\Repositories\Brand\BrandRepositoryInterface;
use App\Services\BaseService;

class BrandService extends BaseService implements BrandServiceInterface
{
    public function __construct(BrandRepositoryInterface $brandRepository){
        $this->repository = $brandRepository;
    }
}
