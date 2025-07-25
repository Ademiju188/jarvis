<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->string('consultant_name');
            $table->text('context');
            $table->text('activities_undertaken');
            $table->text('key_findings');
            $table->text('recommendations');
            $table->date('next_visit_date')->nullable();
            $table->enum('status', ['draft', 'pending_review', 'approved', 'finalised'])->default('draft');
            $table->text('headteacher_feedback')->nullable();
            $table->string('share_token')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
