<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLabel extends Model
{
    protected $fillable = ['image', 'raw_text', 'items'];

protected $casts = [
    'items' => 'array',
];

}
