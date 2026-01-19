<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');

            $table->string('product_name');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->unsignedInteger('qty');
            $table->string('note')->nullable();
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
            $table->index(['transaction_id','product_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('transaction_items'); }
};
