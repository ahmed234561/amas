<div class="card rounded-0 border shadow-none">
    <div class="card-header pt-4 pb-1 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fs-16 fw-700 mb-0">{{ translate('Summary') }}</h3>
            <!-- Items Count -->
            <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                {{ count($carts) }} {{ translate('Items') }}
            </span>
        </div>

        <!-- Minimum Order Amount Warning -->
        @php
            $subtotal_for_min_order_amount = 0;
            foreach ($carts as $key => $cartItem) {
                $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
            }
        @endphp
       
        @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
            <div class="alert alert-warning mt-2 mb-0 py-1 px-2 fs-12">
                <i class="las la-exclamation-circle mr-1"></i>
                {{ translate('Minimum order amount is') }} {{ single_price(get_setting('minimum_order_amount')) }}
            </div>
        @endif
    </div>

    <!-- Club point -->
    @if (addon_is_activated('club_point'))
    <div class="px-4 pt-3 pb-2 border-bottom">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fs-14 fw-700 mb-0">{{ translate('Total Clubpoint') }}</h3>
            <div class="d-flex align-items-center">
                @php
                    $total_point = 0;
                    foreach ($carts as $key => $cartItem) {
                        $product = get_single_product($cartItem['product_id']);
                        $total_point += $product->earn_point * $cartItem['quantity'];
                    }
                @endphp
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" class="mr-1">
                    <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                      <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"/>
                      <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                        <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)" fill="#f3af3d"/>
                        <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)" fill="#f3af3d" opacity="0.5"/>
                        <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"/>
                      </g>
                    </g>
                </svg>
                <span class="fw-600">{{ $total_point }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="card-body">
        <!-- Products Info -->
        <div class="mb-4">
            <h4 class="fs-14 fw-600 mb-3">{{ translate('Order Items') }}</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-top-0 border-bottom pl-0 fs-12 fw-400 opacity-60 text-nowrap">{{ translate('Product') }}</th>
                            <th class="border-top-0 border-bottom pr-0 fs-12 fw-400 opacity-60 text-right">{{ translate('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                            $tax = 0;
                            $shipping = 0;
                            $product_shipping_cost = 0;
                        @endphp
                        @foreach ($carts as $key => $cartItem)
                            @php
                                $product = get_single_product($cartItem['product_id']);
                                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                                $product_shipping_cost = $cartItem['shipping_cost'];
                                $shipping += $product_shipping_cost;

                                $product_name_with_choice = $product->getTranslation('name');
                                if ($cartItem['variant'] != null) {
                                    $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                                }

                            @endphp
                             <tr class="cart_item">
                                <td class="pl-0 fs-13 text-dark border-top-0 border-bottom">
                                    {{ $product_name_with_choice }}
                                    <strong class="d-block text-muted fs-11">
                                        × {{ $cartItem['quantity'] }}
                                    </strong>
                                </td>
                                <td class="pr-0 fs-13 text-primary fw-600 border-top-0 border-bottom text-right">
                                    {{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" id="sub_total" value="{{ $subtotal }}">

        <!-- Order Summary -->
        <div class="border-top pt-3">
            <h4 class="fs-14 fw-600 mb-3">{{ translate('Order Summary') }}</h4>
            <table class="table">
                <tbody>
                    <!-- Subtotal -->
                    <tr>
                        <th class="pl-0 fs-13 text-dark fw-500 border-top-0">{{ translate('Subtotal') }}</th>
                        <td class="pr-0 fs-13 text-primary fw-600 border-top-0 text-right">
                            {{ single_price($subtotal) }}
                        </td>
                    </tr>

                    <!-- Tax -->
                    <tr>
                        <th class="pl-0 fs-13 text-dark fw-500 border-top-0">{{ translate('Tax') }}</th>
                        <td class="pr-0 fs-13 text-primary fw-600 border-top-0 text-right">
                            {{ single_price($tax) }}
                        </td>
                    </tr>

                    <!-- Shipping -->
                    <tr>
                        <th class="pl-0 fs-13 text-dark fw-500 border-top-0">{{ translate('Shipping') }}</th>
                        <td class="pr-0 fs-13 text-primary fw-600 border-top-0 text-right">
                            {{ single_price($shipping) }}
                        </td>
                    </tr>

                    <!-- Coupon Discount -->
                    @php
                        $coupon_discount = 0;
                        if (Auth::check() && get_setting('coupon_system') == 1) {
                            $coupon_discount = $carts->sum('discount');
                        }
                    @endphp
                    @if ($coupon_discount > 0)
                        <tr>
                            <th class="pl-0 fs-13 text-dark fw-500 border-top-0">{{ translate('Coupon Discount') }}</th>
                            <td class="pr-0 fs-13 text-danger fw-600 border-top-0 text-right">
                                -{{ single_price($coupon_discount) }}
                            </td>
                        </tr>
                    @endif

                    <!-- Club Points -->
                    @if (addon_is_activated('club_point') && Session::has('club_point'))
                        <tr>
                            <th class="pl-0 fs-13 text-dark fw-500 border-top-0">{{ translate('Redeem Points') }}</th>
                            <td class="pr-0 fs-13 text-danger fw-600 border-top-0 text-right">
                                -{{ single_price(Session::get('club_point')) }}
                            </td>
                        </tr>
                    @endif

                    <!-- Pickup Point -->
                    @php
                        $pickup_point = null;
                        foreach ($carts as $one_cart) {
                            if($one_cart->shipping_type == "pickup_point") {
                                $pickup_point = \App\Models\PickupPoint::find($one_cart->pickup_point);
                            }
                        }
                    @endphp
                    @if($pickup_point != null)
                        <tr>
                            <th class="pl-0 fs-13 text-dark fw-500 border-top-0">
                                {{ $pickup_point->getTranslation('name') }}
                            </th>
                            <td class="pr-0 fs-13 text-primary fw-600 border-top-0 text-right">
                                {{ $pickup_point->phone }}
                            </td>
                        </tr>
                    @endif

                    <!-- Grand Total -->
                    @php
                        $total = $subtotal + $tax + $shipping;
                        if (Session::has('club_point')) {
                            $total -= Session::get('club_point');
                        }
                        if ($coupon_discount > 0) {
                            $total -= $coupon_discount;
                        }
                        if($pickup_point != null) {
                            $total += $pickup_point->phone;
                        }
                    @endphp
                    <tr>
                        <th class="pl-0 fs-14 text-dark fw-600 pt-2">
                            {{ translate('Total') }}
                        </th>
                        <td class="pr-0 fs-14 text-primary fw-700 pt-2 text-right">
                            {{ single_price($total) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Coupon Section -->
        @if (Auth::check() && get_setting('coupon_system') == 1)
            @php
                $coupon_code = null;
                foreach ($carts as $key => $cartItem) {
                    if ($cartItem->coupon_applied == 1) {
                        $coupon_code = $cartItem->coupon_code;
                        break;
                    }
                }
            @endphp

            @if ($coupon_discount > 0 && $coupon_code)
                <div class="mt-4">
                    <form id="remove-coupon-form" class="input-group">
                        @csrf
                        <input type="text" class="form-control" value="{{ $coupon_code }}" readonly>
                        <div class="input-group-append">
                            <button type="button" id="coupon-remove" class="btn btn-outline-primary">
                                {{ translate('Remove') }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-4">
                    <form id="apply-coupon-form" class="input-group">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                        <input type="text" class="form-control" name="code"
                               placeholder="{{ translate('Coupon code') }}" required>
                        <div class="input-group-append">
                            <button type="button" id="coupon-apply" class="btn btn-primary">
                                {{ translate('Apply') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        <!-- Club Points Section -->
        @if (addon_is_activated('club_point') && Session::has('club_point'))
            <div class="mt-3">
                <form action="{{ route('checkout.remove_club_point') }}" method="POST" class="input-group">
                    @csrf
                    <input type="text" class="form-control" value="{{ Session::get('club_point') }}" readonly>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary">
                            {{ translate('Remove Points') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
