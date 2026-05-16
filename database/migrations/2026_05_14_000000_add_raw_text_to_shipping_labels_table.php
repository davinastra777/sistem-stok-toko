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
        if (! Schema::hasColumn('shipping_labels', 'raw_text')) {
            Schema::table('shipping_labels', function (Blueprint $table) {
                $table->text('raw_text')->nullable()->after('image_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('shipping_labels', 'raw_text')) {
            Schema::table('shipping_labels', function (Blueprint $table) {
                $table->dropColumn('raw_text');
            });
        }
    }
};
