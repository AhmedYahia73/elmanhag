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
        Schema::create('promo_code_bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bundle_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_bundles');
    }
};
