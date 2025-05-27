<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // ✅ أضف هذا هنا
            $table->string('password');
            $table->enum('user_type', ['job_owner', 'artisan']);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
