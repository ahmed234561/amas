<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id'];

    // Add price mutator to ensure we never save zero prices
    public function setPriceAttribute($value)
    {
        if ($value <= 0) {
            // Check for special price
            if (auth()->check() && $this->product_id) {
                $special_price = \App\Models\UserSpecialPrice::where('user_id', auth()->id())
                    ->where('product_id', $this->product_id)
                    ->where('special_price', '>', 0)
                    ->first();

                if ($special_price) {
                    $this->attributes['price'] = $special_price->special_price;
                    return;
                }
            }
        }
        $this->attributes['price'] = $value;
    }

    protected $fillable = [
        'address_id',
        'price',
        'tax',
        'shipping_cost',
        'discount',
        'product_referral_code',
        'coupon_code',
        'coupon_applied',
        'quantity',
        'user_id',
        'temp_user_id',
        'owner_id',
        'product_id',
        'variation',
        'target_points',
        'malaysian_points',
        'saudi_points',
        'client_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
