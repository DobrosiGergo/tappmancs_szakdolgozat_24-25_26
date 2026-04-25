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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('species_id')->references('id')->on('species');
            $table->date('birth_date');
            $table->date('arrival_date');
            $table->foreignId('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('shelter_id')->references('id')->on('shelter')->onDelete('cascade');
            $table->enum('status', ['adopted', 'free']);
            $table->mediumText('description');
            $table->json('images');
            $table->foreignId('breed_id')->references('id')->on('breeds');
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
