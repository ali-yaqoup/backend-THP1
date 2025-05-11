<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
//        $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
//        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
//        $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
        $table->tinyInteger('rating')->check('rating >= 1 AND rating <= 5');
        $table->text('comment')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
