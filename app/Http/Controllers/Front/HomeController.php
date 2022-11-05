<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\BlogService\BlogServiceInterface;
use App\Services\Product\ProductServiceInterface;

class HomeController extends Controller
{
    private $productService;
    private $blogService;

    public function __construct(ProductServiceInterface $productService , BlogServiceInterface $blogService)
    {
        $this->productService = $productService;
        $this->blogService = $blogService;
    }

    public function index(){


        $featuredProducts = $this->productService->getFeaturedProducts();
        $blogs = $this->blogService->getLastestBlogs();

        return view('front.index',compact('featuredProducts','blogs'));
    }

}
