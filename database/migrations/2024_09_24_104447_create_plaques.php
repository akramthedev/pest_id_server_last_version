<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plaques', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->foreignId('serre_id')->constrained('serres')->onDelete('cascade');
            $table->string('name');                  
            $table->timestamps();                    
        });
    }

    public function down(): void
    {
        Schema::table('plaques', function (Blueprint $table) {
            $table->dropForeign(['farm_id']);  
            $table->dropForeign(['serre_id']);  
        });
        Schema::dropIfExists('plaques');  
    }
};
