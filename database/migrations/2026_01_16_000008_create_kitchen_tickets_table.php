<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kitchen_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->string('ticket_no')->unique();
            $table->unsignedInteger('queue_no');
            $table->date('queue_date')->index();
            $table->enum('status', ['new','preparing','ready','served','canceled'])->default('new');
            $table->unsignedInteger('printed_count')->default(0);
            $table->timestamp('last_printed_at')->nullable();
            $table->timestamps();
            $table->unique('transaction_id');
            $table->index(['queue_date','queue_no']);
        });
    }
    public function down(): void { Schema::dropIfExists('kitchen_tickets'); }
};
