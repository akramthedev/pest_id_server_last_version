<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');   
            $table->string('name');                  
            $table->string('location')->nullable();                
            $table->float('size')->nullable();                   
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->dropForeign(['user_id']); 
        });
        
        Schema::dropIfExists('farms');  
    }
};
