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
        Schema::create('otoritas_moduls', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            // $table->string("id", 100)->nullable(false)->primary();
            $table->string("id_menu", 100)->nullable(false);
            $table->string("id_role", 100)->nullable(false);
            $table->integer('view_otoritas_modul')->nullable();
            $table->integer('insert_otoritas_modul')->nullable();
            $table->integer('update_otoritas_modul')->nullable();
            $table->integer('delete_otoritas_modul')->nullable();
            $table->integer('export_otoritas_modul')->nullable();
            $table->integer('import_otoritas_modul')->nullable();
            $table->integer('data_otoritas_modul')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otoritas_moduls');
    }
};
