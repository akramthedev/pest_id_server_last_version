<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create the users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();  
            $table->string('fullName')->nullable();  
            $table->string('email')->unique();   
            $table->string('password');
            $table->string('mobile')->nullable(); 
            $table->enum('type', ['superadmin', 'admin', 'staff']);
            $table->text('image')->nullable();
            $table->boolean('canAccess')->default(0); 
            $table->boolean('isEmailVerified')->default(0); 
            $table->boolean('is_first_time_connected')->default(0); 
            $table->boolean('historique_notice')->default(0);
            $table->boolean('mesfermes_notice')->default(0);
            $table->boolean('mespersonels_notice')->default(0);
            $table->boolean('dashboard_notice')->default(0);
            $table->boolean('isJournalActivitySeen')->default(0);
            $table->boolean('profile_notice')->default(0);
            $table->boolean('liste_users_notice')->default(0);
            $table->boolean('nouvelle_demande_notice')->default(0);
            $table->boolean('isBroadcastSeen')->default(0);
            $table->boolean('isNoticeOfBroadCastSeen')->default(0);
            $table->boolean('is_np')->default(1);
            $table->boolean('is_an')->default(1);
            $table->boolean('is_maj')->default(1);
            $table->boolean('is_ja')->default(1);
            $table->string('otp')->nullable(); 
            $table->boolean('isSeen')->default(false);
            $table->timestamps();  
        });
    }

    public function down(): void
    {
        // Drop the users table and the added columns on rollback
        Schema::dropIfExists('users');
    }
};
