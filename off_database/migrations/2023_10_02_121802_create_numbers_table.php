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
        Schema::create('numbers', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->id('numberID');
            $table->string('providerID', 100);
            $table->string('number', 100);
            $table->string('creditlimit', 100);
            $table->string('mincom', 100);
            $table->string('customerID', 100);
            $table->string('typeNet', 100);
            $table->string('typeWay', 100);
            $table->string('typeNum', 100);
            $table->string('statusNumber', 100);
            $table->string('notes', 100);
            $table->string('useType', 100);
            $table->string('dateCreated', 100);
            $table->string('dateModified', 100);
            $table->string('statusData', 100);
            
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
        Schema::dropIfExists('numbers');
    }
};
