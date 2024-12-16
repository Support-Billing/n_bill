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
        Schema::create('customer_price_backups', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id('idx');
            $table->integer('idxCustomer');
            $table->string('prefixName');
            $table->decimal('tarifPerMenit', $precision = 15, $scale = 2);
            $table->boolean('timeLimit');
            $table->time('startTime');			
            $table->time('endTime');			
            $table->integer('detikAwal');
            $table->integer('detikUnit');	
            $table->integer('idportal');
            $table->boolean('active');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_price_backups');
    }
};
