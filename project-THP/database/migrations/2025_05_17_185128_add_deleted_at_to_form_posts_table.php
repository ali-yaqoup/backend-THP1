<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::table('form_posts', function (Blueprint $table) {
            $table->softDeletes(); // يضيف deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('form_posts', function (Blueprint $table) {
            $table->dropSoftDeletes(); // يحذف deleted_at
        });
    }
};
