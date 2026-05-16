<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'harga_offline')) {
                $table->decimal('harga_offline', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('products', 'harga_online')) {
                $table->decimal('harga_online', 15, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'harga_offline')) {
                $table->dropColumn('harga_offline');
            }
            if (Schema::hasColumn('products', 'harga_online')) {
                $table->dropColumn('harga_online');
            }
        });
    }
};