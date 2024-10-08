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
        Schema::create('lives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->time('from');
            $table->time('to');
            $table->date('date');
            $table->string('day');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('subject_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('education_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->boolean('paid');
            $table->float('price');
            $table->boolean('inculded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lives');
    }
};
