<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');   
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');   
            $table->timestamps();        
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');  
    }
};
