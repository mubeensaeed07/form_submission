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
        Schema::table('form_submissions', function (Blueprint $table) {
            // Loan-specific fields
            $table->unsignedInteger('loan_amount')->nullable()->after('assistance_amount');
            $table->string('loan_purpose')->nullable()->after('loan_amount');
            $table->unsignedSmallInteger('loan_term')->nullable()->after('loan_purpose'); // in months
            
            // Grant-specific fields
            $table->unsignedInteger('grant_amount')->nullable()->after('loan_term');
            $table->string('grant_purpose')->nullable()->after('grant_amount');
            $table->string('grant_category')->nullable()->after('grant_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn(['loan_amount', 'loan_purpose', 'loan_term', 'grant_amount', 'grant_purpose', 'grant_category']);
        });
    }
};
