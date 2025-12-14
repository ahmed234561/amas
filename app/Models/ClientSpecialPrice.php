<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSpecialPrice extends Model
{
    protected $fillable = [
        'client_id',
        'product_id',
        'special_price',
        'added_by',    // يمكن أن تكون 'admin' أو 'seller'
        'user_id'      // معرف المستخدم (الأدمن أو البائع) الذي أضاف السعر
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
