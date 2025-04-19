@extends('frontend.layouts.app')

@section('content')
    <!-- Steps -->

    <!-- Cart Details -->
    <section class="my-4" id="cart-summary">
        @include('frontend.'.get_setting('homepage_select').'.partials.cart_details', ['carts' => $carts])
        <br>
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('checkout.shipping_info') }}" class="btn btn-primary btn-sm  rounded-4">
                            {{ translate('Checkout') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>





    </section>
@endsection

