<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Appends these two new fields to your existing table structure
            $table->string('role')->default('customer');
            $table->string('status')->default('active');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Safe rollback mechanism: deletes only these columns if needed
            $table->dropColumn(['role', 'status']);
        });
    }
};
