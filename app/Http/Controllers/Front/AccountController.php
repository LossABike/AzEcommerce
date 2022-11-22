<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Order\OrderServiceInterface;
use App\Services\User\UserServiceInterface;
use App\Utilities\Constant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    private $userService;
    private $orderService;

    public function __construct(UserServiceInterface $userService,OrderServiceInterface $orderService)
    {
        $this->userService = $userService;
        $this->orderService = $orderService;
    }

    public function login()
    {
        return view('front.account.login');
    }

    public function checkLogin(Request $request)
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'level' => [Constant::user_level_client,Constant::user_level_admin] // level CUSTOMER
        ];

        $remember = $request->remember;

        if (Auth::attempt($credentials, $remember)) {
            return redirect('/cart');
        } else {
            return back()->with('notification', 'Email or Password is not correct Or !!Account was Banned!!');
        }
    }

    public function logout()
    {
        Auth::logout();
        Cart::destroy();
        return back();
    }
    //get
    public function register(){
        return view('front.account.register');
    }

    public function checkEmailExist(Request $request){
        if($request->ajax()){
            $response = [];
            if($this->userService->checkExistAccount($request->email,Constant::user_level_client)){
               $response['notification'] = "Email is Exist";
            }
            return $response;
        }
    }

    public function postRegister(Request $request){


        if($this->userService->checkExistAccount($request->email,Constant::user_level_client)){
            return back()->with('notification','ERROR : Account is Exist !!!');
        }

        //handle pass vs confirm pass && form is null
        if($request->password != $request->password_confirmation || $request->name =='' || $request->email =='' || $request->password =='' ){
            return back()->with('notification','Error .Please fill full form & check your password and submit again');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => Constant::user_level_client, //level CUSTOMER
        ];
        //create User
        $this->userService->create($data);
        return redirect('account/login')->with('notification','Register successfully ! Let login');
    }

    public function myOrderIndex(){
        $orders = $this->orderService->getOrderByUserId(Auth::id());
        return view('front.account.my-order.index',compact('orders'));
    }

    public function myOrderShow($id){
        $order = $this->orderService->find($id);
        return view('front.account.my-order.show',compact('order'));
    }

}
