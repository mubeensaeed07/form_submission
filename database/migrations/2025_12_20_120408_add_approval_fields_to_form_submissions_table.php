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
            $table->enum('status', ['pending', 'approved', 'incorrect'])->default('pending')->after('facebook_user_id');
            $table->text('approved_url')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by_id')->nullable()->after('approved_url');
            $table->timestamp('approved_at')->nullable()->after('approved_by_id');
            
            $table->foreign('approved_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['approved_by_id']);
            $table->dropColumn(['status', 'approved_url', 'approved_by_id', 'approved_at']);
        });
    }
};
