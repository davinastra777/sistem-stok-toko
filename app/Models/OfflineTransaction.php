<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    protected $fillable = ['transaction_date', 'total_amount', 'notes'];

    public function items()
    {
        return $this->hasMany(OfflineTransactionItem::class, 'offline_transaction_id');
    }
}
