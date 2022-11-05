<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductCategory\ProductCategoryServiceInterface;
use App\Services\ProductDetail\ProductDetailServiceInterface;
use App\Utilities\Common;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $productService;
    private $brandService;
    private $productCategoryService;
    private $productDetailService;

    public function __construct(ProductServiceInterface $productService , BrandServiceInterface $brandService , ProductCategoryServiceInterface $productCategoryService ,ProductDetailServiceInterface $productDetailService)
    {
        $this->productService = $productService;
        $this->brandService = $brandService;
        $this->productCategoryService = $productCategoryService;
        $this->productDetailService = $productDetailService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->productService->searchAndPaginate('name',$request->get('search'));
        return view('admin.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        $brands = $this->brandService->all();
        $productCategories = $this->productCategoryService->all();
        return view('admin.product.create',compact('brands','productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        //handle product
        $dataProduct = [
            'brand_id' => $request->input('brand_id'),
            'product_category_id' => $request->input('product_category_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'qty' => 0,
            'discount' => $request->input('discount'),
            'weight' => $request->input('weight'),
            'sku' => $request->input('sku'),
            'featured' => $request->input('featured'),
            'tag' => $request->input('tag'),

        ];
        $product = $this->productService->create($dataProduct);

        $sizes = [
            'S' => $request->input('sizeS') ?? 0,
            'XS' => $request->input('sizeXS') ?? 0,
            'M' => $request->input('sizeM') ?? 0,
            'L' => $request->input('sizeL') ?? 0,
            'XL' => $request->input('sizeXL')?? 0,
        ];
        //handle productDetails

        //total qty in productDetails
        $countQty = 0;
        foreach($sizes as $size=>$qty){

            $dataProductDetail = [
                'product_id' => $product->id,
                'color' => $request->color,
                'size' => $size,
                'qty' => $qty,
            ];

            if($dataProductDetail['qty'] != 0 && $dataProductDetail['qty']>0){
                $this->productDetailService->create($dataProductDetail);
                $countQty += $qty;
            }

        }

        //handle product qty available
        $dataProduct['qty'] = $countQty;
        $this->productService->update($dataProduct,$product->id);

        //handle image
        $path = 'front/img/products';
        $productImage = [];
        if($request->hasFile('image')){
            $productImage = [
                'path' => Common::uploadFile($request->file('image'),$path),
                'product_id' => $product->id,
            ];
        }

        ProductImage::create($productImage);

        return redirect('admin/product/'.$product->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productService->find($id);
        return view('admin.product.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productService->find($id);
        $productDetails = $product->ProductDetails;
        $brands = $this->brandService->all();
        $productCategories = $this->productCategoryService->all();
        return view('admin.product.edit',compact('product','productDetails','brands','productCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update productDetail -> product -> productImage


        //check update size
        $sizes = [
            'S' => $request->input('sizeS') ,
            'XS' => $request->input('sizeXS') ,
            'M' => $request->input('sizeM') ,
            'L' => $request->input('sizeL') ,
            'XL' => $request->input('sizeXL'),
        ];

        //handle productDetails

        //handle add size
        foreach($sizes as $size=>$qty){

            $dataProductDetail = [
                'product_id' => $id,
                'color' => $request->color,
                'size' => $size,
                'qty' => $qty,
            ];
            //handle size not update ('00' is default not update)
            if($dataProductDetail['qty'] != 0 && $dataProductDetail['qty']>0){
                //get productDetail
                $detailId = ProductDetail::where('product_id',$id)->where('size',$size)->value('id');

                //handle size exist => update qty,color
                if(ProductDetail::where('product_id',$id)->where('size',$size)->exists()){
                    $this->productDetailService->update($dataProductDetail,$detailId);
                }else {

                    //if size not exist => create new size and qty
                    //$dataProductDetail['product_id'] = $id;
                    $this->productDetailService->create($dataProductDetail);
                }
            }
            //handle set qty==0 => delete
            if($dataProductDetail['qty'] == 0){
                ProductDetail::where('product_id',$id)->where('size',$size)->delete();
            }

        }


        //handle product
        $dataProduct = [
            'brand_id' => $request->input('brand_id'),
            'product_category_id' => $request->input('product_category_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'qty' => array_sum(array_column($this->productService->find($id)->productDetails->toArray(),'qty')),
            'discount' => $request->input('discount'),
            'weight' => $request->input('weight'),
            'sku' => $request->input('sku'),
            'featured' => $request->input('featured'),
            'tag' => $request->input('tag'),

        ];
        //update product
        $this->productService->update($dataProduct,$id);


        //handle productImage
        $path = 'front/img/products/';
        $dataProductImg = [];
        if($request->hasFile('image')){

            //Handle Image Product

            //delete all old file
            foreach($this->productService->find($id)->productImages as $productImage){

                    unlink($path . $productImage->path);
                    ProductImage::where('id',$productImage->id)->delete();
            }

            //add new img product
            $dataProductImg['path'] = Common::uploadFile($request->file('image'),$path);
            $dataProductImg['product_id'] = $id;

            ProductImage::create($dataProductImg);

        }

        return redirect('admin/product/'.$id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //hide this product
        $data = [
            'qty' => 0,
        ];
        $this->productService->update($data,$id);
        return redirect('admin/product');

    }
}
