@extends('front.layout.master')

@section('title','My Order')

@section('body')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="/"><i class="fa fa-home"></i> Home</a>
                        <span>My Order</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">

                    <div class="col-lg-12">
                        <div class="cart-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Order Id</th>
                                        <th>Product Name</th>
                                        <th>Total</th>
                                        <th>Details</th>

                                    </tr>
                                </thead>
                                @foreach($orders as $order)
                                <tbody>

                                        <tr>
                                            <td class="cart-pic first-row">
                                                <img style="height:200px; margin-left:20px;"
                                                     src="front/img/products/{{$order->orderDetails[0]->product->productImages[0]->path}}" alt=""></td>
                                            <td class="p-price first-row">#{{$order->orderDetails[0]->order_id}}</td>
                                            <td class="p-price first-row">
                                                <h5>{{$order->orderDetails[0]->product->name}}
                                                     @if(count($order->orderDetails)>1)
                                                        <br>(and {{count($order->orderDetails)}} products)
                                                     @endif
                                                </h5>
                                            </td>
                                            <td class="total-price first-row">${{array_sum(array_column($order->orderDetails->toArray(),'total'))}}</td>
                                            <td class="p-price first-row">
                                                <a class="btn" href="./account/my-order/{{$order->id}}">Details</a>
                                            </td>
                                        </tr>

                                </tbody>
                                @endforeach
                            </table>
                        </div>

                    </div>

            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection
