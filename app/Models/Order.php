<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function club_point()
    {
        return $this->hasMany(ClubPoint::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }

    public function additional_info_data($class = "")
    {
        $jsonData = json_decode($this->additional_info, true);

       // dd($jsonData);

        $tableHtml = '<table class="'.$class.'">';
        $tableHtml .= '<thead>';
        $tableHtml .= '<tr>';
        $tableHtml .= '<th>'.translate('Name').'</th>';
        $tableHtml .= '<th>'.translate('Product').'</th>';
        $tableHtml .= '<th>'.translate('Quantity').'</th>';
        $tableHtml .= '</tr>';
        $tableHtml .= '</thead>';
        $tableHtml .= '<tbody>';

        foreach ($jsonData as $purchase) {
            foreach ($purchase['purchases'] as $item) {
                $tableHtml .= '<tr>';
                $tableHtml .= '<td>' . ($purchase['name'] == "" ? translate('Me') : $purchase['name']). '</td>';
                $tableHtml .= '<td>' . $item['product_name'] . '</td>';
                $tableHtml .= '<td>' . $item['count'] . '</td>';
                $tableHtml .= '</tr>';
            }
        }

        $tableHtml .= '</tbody>';
        $tableHtml .= '</table>';

        return $tableHtml;
    }
}
