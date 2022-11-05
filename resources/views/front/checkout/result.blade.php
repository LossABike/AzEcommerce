@extends('front.layout.master')

@section('title','Result')

@section('body')
    <!-- Shopping Cart Section Begin -->
    <section class="checkout-section spad">
        <div class="container">


                <div class="row">

                    <div class="col-lg-12">
                            <h5>{{$notification}}</h5>
                        <a href="./" class="primary-btn mt-5"> Continue Shopping</a>
                    </div>

                </div>

        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection
