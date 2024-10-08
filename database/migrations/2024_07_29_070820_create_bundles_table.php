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
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->string('tags')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('demo_video')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->enum('semester', ['first', 'second']);
            $table->foreignId('category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('education_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->date('expired_date');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
