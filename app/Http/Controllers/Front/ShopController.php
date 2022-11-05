<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductCategory\ProductCategoryServiceInterface;
use App\Services\ProductComment\ProductCommentServiceInterface;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //dependent inject
    private $productService;
    private $productCommentService;
    private $productCategoryService;
    private $brandService;
    public function __construct(ProductServiceInterface $productService,
                                ProductCommentServiceInterface $productCommentService,
                                ProductCategoryServiceInterface $productCategoryService,
                                BrandServiceInterface $brandService,
    ){
        $this->productService = $productService;
        $this->productCommentService = $productCommentService;
        $this->productCategoryService = $productCategoryService;
        $this->brandService = $brandService;
    }
    public function show($id){
        $brands = $this->brandService->all();
        $categories = $this->productCategoryService->all();
        $product = $this->productService->find($id);
        $relatedProducts = $this->productService->getRalatedProducts($product);
        return view('front.shop.show',compact('product','relatedProducts','brands','categories'));
    }
    public function postComment(Request $request){
        $this->productCommentService->create($request->all());
        return redirect()->back();

    }
    public function index(Request $request){
        $brands = $this->brandService->all();
        $products = $this->productService->getProductOnIndex($request);
        $categories = $this->productCategoryService->all();
        return view('front.shop.index',compact('products','categories','brands'));
    }
    public function category($categoryName , Request $request){
        $products = $this->productService->getProductsByCategory($categoryName,$request);
        //Brands
        $brands = $this->brandService->all();
        $categories = $this->productCategoryService->all();
        return view('front.shop.index',compact('products','categories','brands'));
    }
}
