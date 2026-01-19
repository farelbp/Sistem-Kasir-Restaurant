<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();

            $table->enum('method', ['cash','transfer_manual'])->default('cash');
            $table->enum('status', ['paid','pending_verification'])->default('paid');

            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('cash_received', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);

            $table->string('reference_no')->nullable();
            $table->string('proof_url')->nullable();

            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->unique('transaction_id');
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
