<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfflineTransaction extends Model
{
    protected $fillable = ['transaction_date', 'total_amount', 'notes'];

    public function items()
    {
        return $this->hasMany(OfflineTransactionItem::class, 'offline_transaction_id');
    }

    protected static function booted()
    {
        // Event ini dipicu tepat sebelum data OfflineTransaction dihapus
        static::deleting(function ($transaction) {
            DB::transaction(function () use ($transaction) {
                // 1. Ambil semua item terkait dan kembalikan stoknya ke tabel produk
                foreach ($transaction->items as $item) {
                    $product = Product::find($item->product_id);
                    
                    if ($product) {
                        // Tambahkan kembali stok sesuai qty yang dihapus
                        $product->increment('jumlah stok', $item->qty);
                    }
                }

                // Hapus detail item transaksi secara manual
                $transaction->items()->delete();
            });
        });
    }
}