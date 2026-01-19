<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->date('bill_date')->nullable()->index();
            $table->unsignedInteger('bill_running_no')->nullable();
            $table->string('bill_no')->nullable()->unique();

            $table->date('queue_date')->nullable()->index();
            $table->unsignedInteger('queue_no')->nullable();

            $table->enum('status', ['draft','sent_to_kitchen','pending_verification','paid','canceled'])->default('draft')->index();

            $table->foreignId('cashier_id')->constrained('users');
            $table->foreignId('table_id')->nullable()->constrained('tables');

            $table->enum('order_type', ['dine_in','takeaway','delivery'])->default('dine_in');
            $table->string('customer_name')->nullable();
            $table->text('notes')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('service', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->timestamp('sent_to_kitchen_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['queue_date','queue_no']);
        });
    }
    public function down(): void { Schema::dropIfExists('transactions'); }
};
