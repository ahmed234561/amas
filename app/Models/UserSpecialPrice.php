<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpecialPrice extends Model
{
    protected $table = 'user_special_prices';
    protected $fillable = [
        'user_id',
        'product_id',
        'special_price',
        'added_by'
    ];
    protected $casts = ['special_price' => 'float'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
