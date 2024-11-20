<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serres', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->string('name');                  
            $table->float('size')->nullable();                  
            $table->string('type')->nullable();                  
            $table->timestamps();                    
        });
    }

    public function down(): void
    {
        Schema::table('serres', function (Blueprint $table) {
            $table->dropForeign(['farm_id']);  
        });
        Schema::dropIfExists('serres');  
    }
};
