<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\ProductDetail;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductDetail\ProductDetailServiceInterface;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    private $productService;

    public function __construct(ProductServiceInterface $productService ){
        $this->productService = $productService;

    }
    public function add(Request $request){

        if($request->ajax()){

            $product = $this->productService->find($request->productId);

            //check max qty
            $maxQty = ProductDetail::where('product_id',$product->id)->where('size',$request->size)->value('qty') ;

            //check request qty > max

                $response['cart'] = Cart::add([
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $request->qty,
                    'price' => $product->discount ?? $product->price,
                    'weight' => $product->weight ?? 0,
                    'options' => [
                        'images' => $product->productImages,
                        'size' => $request->size,
                    ],
                ]);

            //check qty in Cart [qty in cart after add request->qty]
            $qty_in_cart = Cart::get($response['cart']->rowId)->qty;

            //handle qty in cart > max , request->qty > max
            if( $qty_in_cart > $maxQty ){
                Cart::update($response['cart']->rowId,['qty' => $maxQty]);
                $response['notification'] = "Limit product " . $product->name . " is " . $maxQty;
            }
            //update after Cart update
            $response['count'] = Cart::count();
            $response['total'] = Cart::total();

            return $response;
         }
         return back();
    }

    public function index(){
        $carts = Cart::content();
        $total = Cart::total();
        $subtotal = Cart::subtotal();

        return view('front.shop.cart',compact('carts','total','subtotal'));
    }

    public function delete(Request $request){
        if($request->ajax()){
            Cart::remove($request->rowId);
            $response['count'] = Cart::count();
            $response['total'] = Cart::total();
            $response['subtotal'] = Cart::subtotal();

            return $response;
        }
        return back();
    }

    public function destroy(){
        Cart::destroy();
    }

    public function update(Request $request){
        if($request->ajax()){
            $response['cart'] = Cart::update($request->rowId,$request->qty);
            $response['count'] = Cart::count();
            $response['total'] = Cart::total();
            $response['subtotal'] = Cart::subtotal();
            return $response;
        }
    }

}
