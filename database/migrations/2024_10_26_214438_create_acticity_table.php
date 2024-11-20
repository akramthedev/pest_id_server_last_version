<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  
            $table->string('action');
            $table->boolean('isDanger')->default(false);  
            $table->string('brand')->nullable();  
            $table->string('device_type')->nullable();  
            $table->string('model')->nullable();  
            $table->string('os')->nullable();  
            $table->string('ip_address')->nullable();  
            $table->string('provider')->nullable();
            $table->timestamps();  
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('activity');
    }
};

 