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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            // Step 1 - personal information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();

            // Step 2 - family & financial
            $table->unsignedTinyInteger('household_size')->nullable();
            $table->unsignedTinyInteger('dependents')->nullable();
            $table->json('family_members')->nullable(); // [{name, age, relationship}]
            $table->string('employment_status')->nullable();
            $table->string('employer_name')->nullable();
            $table->unsignedInteger('monthly_income')->nullable();

            // Step 3 - assistance details
            $table->unsignedInteger('assistance_amount')->nullable();
            $table->json('assistance_types')->nullable();
            $table->text('assistance_description')->nullable();

            // Step 4 - verification
            $table->string('ssn')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->boolean('consent')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};

