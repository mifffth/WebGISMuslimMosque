<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     // menambahkan kolom tabel
    public function up(): void
    {
        Schema::table('table_points', function (Blueprint $table) {$table->string('image')->nullable();
        });
        Schema::table('table_polylines', function (Blueprint $table) {$table->string('image')->nullable();
        });
        Schema::table('table_polygons', function (Blueprint $table) {$table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */

     //fungsi down balikin migrasi/rollback
    public function down(): void
    {
        //drop column table
        Schema::table('table_points', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('table_polylines', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('table_polygons', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};