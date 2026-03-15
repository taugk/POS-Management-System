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
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('promo_id')->nullable()->after('notes');
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('set null');

            $table->decimal('discount_amount', 15, 2)->default(0)->after('promo_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn('promo_id');
            $table->dropColumn('discount_amount');
        });
    }
};
