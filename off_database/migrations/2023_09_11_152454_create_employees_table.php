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
        Schema::create('employees', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            // $table->string("id", 100)->nullable(false)->primary();
            // $table->string("id_worklocation", 100);
            $table->uuid('id')->primary();
            // $table->foreignUuid('id_worklocation')->constrained();
            $table->foreignUuid('id_worklocation')->constrained(
                table: 'worklocations', indexName: 'employees_id_worklocation'
            );
            $table->string('name')->nullable();
            $table->string('nik')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string("avatar")->nullable()->comment("berisi nama file image saja tanpa path");
            $table->enum("status", ["ACTIVE", "INACTIVE"]);
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
        Schema::dropIfExists('employees');
    }
};
