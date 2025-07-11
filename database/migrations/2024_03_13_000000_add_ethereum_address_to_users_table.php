<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'address'))
                $table->string('address')->nullable()->unique()->after('email');
            // Ensure we can nullify users and passsword
            if (Schema::hasColumn('users', 'name'))
                $table->string('name')->nullable()->change();
            if (Schema::hasColumn('users', 'password'))
                $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
