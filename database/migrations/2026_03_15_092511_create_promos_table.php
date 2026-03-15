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
       Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('code')->unique(); 
            $table->enum('type', ['percentage', 'fixed']); 
            
            // Mengubah 'value' menjadi 'discount_amount' agar lebih jelas
            $table->decimal('discount_amount', 15, 2); 
            
            // Menambahkan batas maksimal diskon untuk tipe persentase
            $table->decimal('max_discount', 15, 2)->nullable(); 
            
            $table->decimal('min_purchase', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            
            // Opsional: Batasi kuota penggunaan promo
            $table->integer('usage_limit')->nullable(); 
            $table->integer('used_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
