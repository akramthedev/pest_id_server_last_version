<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  
            $table->text('company_name')->nullable();
            $table->text('company_email')->nullable();
            $table->text('company_mobile')->nullable();
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
