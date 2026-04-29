<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Tambahkan baris ini
    protected $fillable = [
        'nama produk',
        'id produk',
        'jumlah stok',
        'harga',
    ];

    public function shippingLabelItems()
    {
        return $this->hasMany(ShippingLabelItem::class);
    }

    public function offlineTransactionItems()
    {
        return $this->hasMany(OfflineTransactionItem::class);
    }

    // Accessor untuk stock
    public function getStockAttribute()
    {
        return $this->{'jumlah stok'};
    }

    public function setStockAttribute($value)
    {
        $this->{'jumlah stok'} = $value;
    }
}
