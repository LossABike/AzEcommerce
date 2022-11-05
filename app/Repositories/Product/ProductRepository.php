<?php

namespace App\Repositories\Product;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Repositories\BaseRepository;

 class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
     public function getModel(){
        return Product::class;
    }
    public function getRalatedProducts($product,$limit = 4){
         return $this->model->where('product_category_id',$product->product_category_id)
         ->where('tag',$product->tag)->where('qty','>',0)->limit($limit)->get();
    }
    public function getFeaturedProductsByCategory($object){
         if($object==1) $object= "Men" ;
         else if($object==2) $object="Women";
         else $object="Kids";

         return $this->model
             ->join('product_categories','products.product_category_id','=','product_categories.id')
             ->where('product_categories.name',$object)
             ->where('products.featured',1)
             ->where('qty','>',0)
             ->select('products.*')
             ->get();
    }
    //pagination page shop
    public function getProductOnIndex($request){

         $search = $request->search ?? '';

         //Handler Qty
         $products = $this->model->where('name','like','%'.$search.'%')->where('qty','>',0);

         $products = $this->filter($products , $request);

         //call Paginate latest
         $products = $this->sortAndPagination($products,$request);

         return $products;
    }

    public function getProductsByCategory($categoryName,$request){
         $products = ProductCategory::where('name',$categoryName)->first()->products->toQuery();
         //handle qty
         $products = $products->where('qty','>',0);

         $products = $this->filter($products , $request);
        //call Paginate latest
         $products = $this->sortAndPagination($products,$request);

         return $products;
    }
    private function sortAndPagination($products,$request){
        $perPage = $request->show ?? 6;
        $sortBy = $request->sort_by ?? 'latest';
        switch($sortBy){
            case 'latest' : $products = $products ->orderBy('id');
                break;
            case 'oldest' : $products = $products ->orderByDesc('id');
                break;
            case 'name-ascending': $products =$products->orderBy('name');
                break;
            case 'name-descending': $products =$products->orderByDesc('name');
                break;
            case 'price-ascending': $products =$products->orderBy('price');
                break;
            case 'price-descending': $products =$products->orderByDesc('price');
                break;
            default : $products = $products ->orderBy('id');
        }
        $products = $products->paginate($perPage);
        $products->appends(['sort_by'=>$sortBy, 'show'=> $perPage]);
        return $products;
    }

    private function filter($products , $request){
        //Brand
        $brands = $request->brand ?? [];
        $brand_ids = array_keys($brands);
        $products = $brand_ids != null ? $products->whereIn('brand_id',$brand_ids) : $products;

        //Price
        $priceMin = $request->price_min;
        $priceMax = $request->price_max;

        $priceMin = str_replace('$','',$priceMin);
        $priceMax = str_replace('$','',$priceMax);

        $products = ($priceMin!=null && $priceMax!=null) ?
            $products->whereBetween('price',[$priceMin,$priceMax])
            : $products;

        //size
        $size = $request->size;
        $products = $size != null ? $products->whereHas('productDetails',function($query) use ($size){
            return $query->where('size',$size);
        }) : $products;

        //Color
        $color = $request->color;
        $products = $color != null ? $products->whereHas('productDetails',function($query) use ($color){
            return $query->where('color',$color);
        }) : $products;




        return $products;
    }

}
