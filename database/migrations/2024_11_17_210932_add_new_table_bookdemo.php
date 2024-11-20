<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookdemo', function (Blueprint $table) {
            $table->id();  
            $table->text('identification')->nullable();                   
            $table->text('fullName')->nullable();                   
            $table->text('mobile')->nullable();  
            $table->text('email'); 
            $table->text('date');   
            $table->text('time')->nullable();
            $table->boolean('isDone')->default(false);
            $table->boolean('isRemote')->default(true);
            $table->boolean('isSeen')->default(false);
            $table->timestamps();                        
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookdemo');  
    }
};
