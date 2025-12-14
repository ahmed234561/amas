<?php

namespace App\Utility;

use App\Models\Cart;
use Cookie;

class CartUtility
{

    public static function create_cart_variant($product, $request)
    {
        $str = null;
        if (isset($request['color'])) {
            $str = $request['color'];
        }

        if (isset($product->choice_options) && count(json_decode($product->choice_options)) > 0) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }
        return $str;
    }

    public static function get_price($product, $product_stock, $quantity)
    {
        // Check for special price first if user is authenticated - return it exactly as is
        if (auth()->check()) {
            $specialPrice = $product->userSpecialPrices()
                ->where('user_id', auth()->id())
                ->where('special_price', '>', 0)
                ->first();

            if ($specialPrice) {
                \Log::info('Using special price without modifications in CartUtility', [
                    'product_id' => $product->id,
                    'special_price' => $specialPrice->special_price,
                    'user_id' => auth()->id()
                ]);
                return floatval($specialPrice->special_price);
            }
        }

        // If no special price, continue with regular pricing
        $price = floatval($product_stock->price);

        if ($product->auction_product == 1) {
            $price = floatval($product->bids->max('amount'));
        }

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices
                ->where('min_qty', '<=', $quantity)
                ->where('max_qty', '>=', $quantity)
                ->first();
            if ($wholesalePrice) {
                $price = floatval($wholesalePrice->price);
            }
        }

        // Only apply discounts for regular prices
        $price = floatval(self::discount_calculation($product, $price));

        \Log::info('Final regular price in CartUtility', [
            'product_id' => $product->id,
            'final_price' => $price
        ]);

        return $price;
    }

    public static function discount_calculation($product, $price)
    {
        $discount_applicable = false;

        if (
            $product->discount_start_date == null ||
            (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date)
        ) {
            $discount_applicable = true;
        }

        if (check_discount($product,$discount_applicable)) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
        return $price;
    }

    public static function tax_calculation($product, $price)
    {
        $tax = 0;
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        return $tax;
    }

public static function save_cart_data($cart, $product, $price, $tax, $quantity)
{
    // تحقق من وجود سعر خاص مباشرة من DB
    if (auth()->check()) {
        $special_price = \App\Models\UserSpecialPrice::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('special_price', '>', 0)
            ->first();

        if ($special_price) {
            $price = floatval($special_price->special_price); // استبدال السعر الخاص
        }
    }

    // تحويل السعر إلى عدد عشري وتحقق من صحته
    $price = floatval($price);

    if ($price <= 0) {
        throw new \Exception('لا يمكن حفظ سعر صفر أو سالب في السلة');
    }

    // حفظ البيانات
    $cart->quantity = $quantity;
    $cart->product_id = $product->id;
    $cart->owner_id = $product->user_id;
    $cart->price = $price;
    $cart->tax = 0;
    $cart->product_referral_code = null;

    if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
        $cart->product_referral_code = Cookie::get('product_referral_code');
    }

    $cart->save();

    \Log::info('تم حفظ البيانات في السلة:', [
        'cart_id' => $cart->id,
        'price' => $cart->price,
        'product_id' => $cart->product_id
    ]);
}


    public static function check_auction_in_cart($carts)
    {
        foreach ($carts as $cart) {
            if ($cart->product->auction_product == 1) {
                return true;
            }
        }

        return false;
    }
}
