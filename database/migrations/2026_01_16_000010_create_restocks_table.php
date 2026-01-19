<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name')->nullable();
            $table->date('restock_date')->index();
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('restocks'); }
};
