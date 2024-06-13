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
        Schema::table('table_points', function (Blueprint $table) {
            $table->dropColumn('geom');
            $table->renameColumn('geom_32749', 'geom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_points', function (Blueprint $table) {
            $table->geometry('geom', 4326);
            $table->dropColumn('geom');
        });
    }
};
