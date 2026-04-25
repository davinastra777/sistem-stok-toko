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
}
