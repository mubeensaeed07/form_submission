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
        Schema::create('facebook_users', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_url');
            $table->string('full_name');
            $table->string('generated_url')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_role'); // 'admin' or 'agent'
            $table->timestamps();
            
            $table->unique('facebook_url');
            $table->index('created_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_users');
    }
};
