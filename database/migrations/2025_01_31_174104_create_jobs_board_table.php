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
        Schema::create('jobs_board', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('company');
            $table->string('company_logo')->nullable();
            $table->string('location');
            $table->string('category');
            $table->string('salary');
            $table->json('qualifications');
            $table->json('descriptions');
            $table->text('benefit')->nullable();
            $table->string('type');
            $table->text('work_condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs_board');
    }
};
