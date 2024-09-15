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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('paid');
            $table->foreignId('chapter_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->boolean('status')->default(1);
            $table->boolean('switch')->default(1);
            $table->integer('order');
            $table->boolean('drip_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
