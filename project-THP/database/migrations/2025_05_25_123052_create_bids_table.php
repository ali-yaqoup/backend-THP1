<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');     // الشخص الذي قدم العرض
            $table->string('job_title');
            $table->string('client_name');
            $table->decimal('price', 10, 2);
            $table->date('submission_date');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->boolean('processed')->default(false);

            $table->timestamps();

            // العلاقات مع الجداول الأخرى
            $table->foreign('post_id')->references('post_id')->on('form_posts')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('bids');
    }
};
