@extends('front.layout.master')

@section('title','Order Detail')

@section('body')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="#"><i class="fa fa-home"></i> Home</a>
                        <span>Order Detail</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->
    <!-- Shopping Cart Section Begin -->
    <section class="checkout-section spad">
        <div class="container">


            <form  class="checkout-form">

                <div class="row">

                    <div class="col-lg-6">
                        <div class="checkout-content">
                           <b> #Order ID: {{$order->id}}</b>
                        </div>
                        <h4>Biiling Details</h4>
                        <div class="row">
                            <input type="hidden" id="user_id" name="user_id" value="{{$order->id}}">
                            <div class="col-lg-6">
                                <label for="fir">First Name</label>
                                <input type="text" disabled id="fir" value="{{$order->first_name ?? ''}}">
                            </div>
                            <div class="col-lg-6">
                                <label for="last">Last Name</label>
                                <input type="text" disabled id="last" value="{{$order->last_name ?? 'jjj'}}">
                            </div>
                            <div class="col-lg-12">
                                <label for="cun-name">Company Name</label>
                                <input type="text" disabled id="cun-name"  value="{{$order->company_name ?? ''}}">
                            </div>
                            <div class="col-lg-12">
                                <label for="cun">Country<span>*</span></label>
                                <input type="text" disabled id="cun"  value="{{$order->country ?? ''}}">
                            </div>
                            <div class="col-lg-12">
                                <label for="street">Street Address</label>
                                <input type="text" disabled id="street" class="street-first" value="{{$order->street_address ?? ''}}">

                            </div>
                            <div class="col-lg-12">
                                <label for="zip">Postcode / ZIP (optional)</label>
                                <input type="text" disabled id="zip" value="{{$order->postcode_zip ?? ''}}">
                            </div>
                            <div class="col-lg-12">
                                <label for="town">Town / City<span>*</span></label>
                                <input type="text" disabled id="town"  value="{{$order->town_city ?? ''}}">
                            </div>
                            <div class="col-lg-6">
                                <label for="email">Email Address<span>*</span></label>
                                <input type="text" disabled id="email"  value="{{$order->email ?? ''}}">
                            </div>
                            <div class="col-lg-6">
                                <label for="phone">Phone<span>*</span></label>
                                <input type="text" disabled id="phone"  value="{{$order->phone ?? ''}}">
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout-content">
                            <a href="#" class="content-btn">
                                Status : <b>{{\App\Utilities\Constant::$order_status[$order->status]}}</b>
                            </a>
                        </div>

                        <div class="place-order">
                            <h4>Your Order</h4>
                            <div class="order-total">
                                <ul class="order-table">
                                    <li>Product <span>Total</span></li>
                                        @foreach($order->orderDetails as $orderDetail)
                                            <li class="fw-normal">{{$orderDetail->product->name}} x {{$orderDetail->qty}} - {{$orderDetail->size}}
                                                <span>${{$orderDetail->amount}} * {{$orderDetail->qty}}</span></li>
                                        @endforeach
                                    <li class="total-price">Total <span>${{array_sum(array_column($order->orderDetails->toArray(),'total'))}}</span></li>
                                </ul>
                                <div class="payment-check">
                                    <div class="pc-item">
                                        <label for="pc-check">
                                            Pay Later
                                            <input disabled type="radio" name="payment_type" value ="pay_later" id="pc-check"
                                                   {{$order->payment_type=='pay_later' ? 'checked' : ''}}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pc-item">
                                        <label for="pc-paypal">
                                            Online Payment
                                            <input disabled type="radio"  name="payment_type" value ="online_payment" id="pc-paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </form>


        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection
