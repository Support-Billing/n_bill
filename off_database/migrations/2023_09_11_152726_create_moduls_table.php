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
        Schema::create('moduls', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->uuid('id')->primary();
            $table->uuid('m_id')->nullable(true);
            $table->string("url", 100)->nullable(true);
            $table->string("name", 100)->nullable(false);
            $table->text("description")->nullable(true);
            $table->string("type", 100)->nullable(true);
            $table->bigInteger("list_number")->nullable(true);
            $table->string("icon", 100)->nullable(true);
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
        Schema::dropIfExists('moduls');
    }
};
