<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');   
            $table->foreignId('farm_id')->nullable()->constrained('farms')->onDelete('cascade');  
            $table->foreignId('serre_id')->nullable()->constrained('serres')->onDelete('cascade'); 
            $table->foreignId('plaque_id')->nullable()->constrained('plaques')->onDelete('cascade'); 
            $table->float('result');         
            $table->timestamps();             
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');  
    }
};
