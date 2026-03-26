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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('first_name', 100)->nullable();
            $table->string('middle_initial', 1)->nullable();  // single letter, no dot
            $table->string('last_name', 100)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('job_title');
            $table->string('office');
            $table->unsignedInteger('id_number');
            $table->string('id_display')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();

            $table->unique('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
