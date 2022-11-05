<?php

namespace App\Http\Controllers\Front;
use App\Utilities\Constant;
use App\Utilities\VNPay;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderServiceInterface;
use App\Services\OrderDetail\OrderDetailServiceInterface;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckOutController extends Controller
{
    private $orderDetailService;
    private $orderService;
    public function index(){
        $carts = Cart::content();
        $total = Cart::total();
        $subtotal = Cart::subtotal();
        return view('front.checkout.index',compact('carts','total','subtotal'));
    }
    public function __construct(OrderDetailServiceInterface $orderDetailService , OrderServiceInterface $orderService){
        $this->orderDetailService = $orderDetailService;
        $this->orderService = $orderService;
    }
    public function addOrder(Request $request){

        //add item
        $data =$request->all();
        $data['status']=Constant::order_status_ReceiveOrders;
        $order = $this->orderService->create($data);
        //info cart
        $carts = Cart::content();
        foreach($carts as $cart){
            $data = [
                'order_id' => $order->id,
                'product_id' => $cart->id,
                'qty' => $cart->qty,
                'size' => $cart->options-> size ?? '',
                'amount' => $cart->price,
                'total' => $cart->qty*$cart->price,
            ];

            $this->orderDetailService->create($data);
        }

        if($request->payment_type == 'pay_later'){
           //Send email notification
            $total = Cart::total();
            $subtotal = Cart::subtotal();

            $this->sendEmail($order,$total,$subtotal);

            //Destroy cart after order success
            Cart::destroy();
            return redirect('checkout/result')
                ->with('notification','Your Order Success! You will pay on delivery. Please check your email. Thank You <3');
        }

        if($request->payment_type == 'online_payment'){
            //step  URL get URL VNPAY -> Redirect to URL
            $data_url = VNPay::vnpay_create_payment([
                'vnp_TxnRef' => $order->id,
                'vnp_OrderInfo' => 'Mo ta order', //describe Order
                'vnp_Amount' => Cart::total(0,'','')*23500, //rate usd
            ]);
            //Redirect to URL VNPAY
            return redirect()->to($data_url);


        }

    }

    public function vnPayCheck(Request $request){
        //step 01 get data URL where VNPAY send though $vnp_Returnurl;
        $vnp_ResponseCode = $request->get('vnp_ResponseCode'); // result 00 == Success
        $vnp_TxnRef = $request->get('vnp_TxnRef'); // Order Id
        $vnp_Amount = $request->get('vnp_Amount'); // Total

        //step 02 check data return
        if($vnp_ResponseCode != null){
            //Sucess
            if($vnp_ResponseCode== 00){
                Cart::destroy();
                return redirect('checkout/result')
                    ->with('notification','Your Order Success! You will pay on delivery. Please check your email. Thank You <3');

            } else {
                //if fail
                //delete order in database (vnp_TxnRef = orderId)
                $this->orderService->delete($vnp_TxnRef);
                //notifi
                return redirect('checkout/result')
                    ->with('notification','ERROR : Payment fail or canceled');

            }
        }
    }

    public function result(){

        $notification = session('notification') ?? '';
        return view('front.checkout.result',compact('notification'));
    }

    private function sendEmail($order,$total,$subtotal){
           $email_to = $order->email;

           Mail::send('front.checkout.email',compact('order','total','subtotal'),
           function ($message) use ($email_to) {
               $message->from('ngoduchieuxxx@gmail.com','AzDigital');
               $message->to($email_to,$email_to);
               $message->subject('Order Notification');
           });
    }
}
