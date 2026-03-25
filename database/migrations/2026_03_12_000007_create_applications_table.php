<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('jobseeker_id')->constrained('jobseekers')->cascadeOnDelete();
            $table->enum('status', ['reviewing', 'shortlisted', 'interview', 'hired', 'rejected'])->default('reviewing');
            $table->unsignedTinyInteger('match_score')->default(0);
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            $table->unique(['job_listing_id', 'jobseeker_id']);
            $table->index('job_listing_id');
            $table->index('jobseeker_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
