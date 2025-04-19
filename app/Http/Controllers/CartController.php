<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;
use App\Models\Carrier;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }
        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }
        $carrier_list = array();
        if (get_setting('shipping_type') == 'carrier_wise_shipping') {
            $zone = \App\Models\Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id;

            $carrier_query = Carrier::where('status', 1);
            $carrier_query->whereIn('id',function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                    ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->get();
        }
        $carts = Cart::where('user_id', Auth::user()->id)
        ->get();
        if ($carts && count($carts) > 0) {
            if ($carts->isEmpty()) {
                flash(translate('Your cart is empty'))->warning();
                return redirect()->route('home');
            }
        }
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        //        if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            // return view('frontend.shipping_info', compact('categories', 'carts'));

            // $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
            $total = 0;
            $tax = 0;
            $shipping = 0;
            $subtotal = 0;

            if ($carts && count($carts) > 0) {
                foreach ($carts as $key => $cartItem) {
                    $product = Product::find($cartItem['product_id']);
                    $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                    $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];

                    if (get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point') {
                        if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                            $cartItem['shipping_type'] = 'pickup_point';
                            $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                        } else {
                            $cartItem['shipping_type'] = 'home_delivery';
                        }
                        $cartItem['shipping_cost'] = 0;
                        if ($cartItem['shipping_type'] == 'home_delivery') {
                            $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                        }
                    } else {
                        $cartItem['shipping_type'] = 'carrier';
                        $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id']);
                    }

                    $shipping += $cartItem['shipping_cost'];
                    $cartItem->save();
                }
                $total = $subtotal + $tax + $shipping;

                return view('frontend.view_cart', compact('carts','categories','carrier_list','carts', 'total'));


            } else {
                flash(translate('Your Cart was empty'))->warning();
                return redirect()->route('home');
            }
        }
        flash(translate('Your cart is empty'))->success();
        return back();



    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.'.get_setting('homepage_select').'.partials.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::find($request->id);
        $carts = array();

        if($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());
        $product_stock = $product->stocks->where('variant', $str)->first();

        $cart = Cart::firstOrNew([
            'variation' => $str,
            'user_id' => auth()->user()->id,
            'product_id' => $request['id']
        ]);

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
                );
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
                );
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

        $carts = Cart::where('user_id', auth()->user()->id)->get();
        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    // public function updateQuantity(Request $request)
    // {
    //     $cartItem = Cart::findOrFail($request->id);

    //     if ($cartItem['id'] == $request->id) {
    //         $product = Product::find($cartItem['product_id']);
    //         $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
    //         $quantity = $product_stock->qty;
    //         $price = $product_stock->price;

    //         //discount calculation
    //         $discount_applicable = false;

    //         if ($product->discount_start_date == null) {
    //             $discount_applicable = true;
    //         } elseif (
    //             strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
    //             strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
    //         ) {
    //             $discount_applicable = true;
    //         }

    //         if (check_discount($product,$discount_applicable)) {
    //             if ($product->discount_type == 'percent') {
    //                 $price -= ($price * $product->discount) / 100;
    //             } elseif ($product->discount_type == 'amount') {
    //                 $price -= $product->discount;
    //             }
    //         }

    //         if ($quantity >= $request->quantity) {
    //             if ($request->quantity >= $product->min_qty) {
    //                 $cartItem['quantity'] = $request->quantity;
    //             }
    //         }

    //         if ($product->wholesale_product) {
    //             $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
    //             if ($wholesalePrice) {
    //                 $price = $wholesalePrice->price;
    //             }
    //         }

    //         $cartItem['price'] = $price;
    //         $cartItem->save();
    //     }

    //     if (auth()->user() != null) {
    //         $user_id = Auth::user()->id;
    //         $carts = Cart::where('user_id', $user_id)->get();
    //     } else {
    //         $temp_user_id = $request->session()->get('temp_user_id');
    //         $carts = Cart::where('temp_user_id', $temp_user_id)->get();
    //     }

    //     return array(
    //         'cart_count' => count($carts),
    //         'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
    //         'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //     );
    // }
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::find($request->id);

        if(!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
        }

        $product = Product::find($cartItem->product_id);
        $stock = $product->stocks->where('variant', $cartItem->variation)->first();

        // Validate quantity
        if($request->quantity < $product->min_qty) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum quantity is '.$product->min_qty,
                'original_quantity' => $cartItem->quantity
            ]);
        }

        if($request->quantity > $stock->qty) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum available quantity is '.$stock->qty,
                'original_quantity' => $cartItem->quantity
            ]);
        }

        // Update quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Calculate new totals
        $itemTotal = cart_product_price($cartItem, $product, false) * $cartItem->quantity;
        $subtotal = Cart::where('user_id', Auth::id())->get()->sum(function($item) {
            $product = Product::find($item->product_id);
            return cart_product_price($item, $product, false) * $item->quantity;
        });


        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully',
            'item_total' => single_price($itemTotal),
            'subtotal' => single_price($subtotal)
        ]);
    }
}
