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
        Schema::create('vsummaries', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id('idx');
            $table->string('filename');	
            $table->datetime('datetime');		
            $table->string('sourceNo');	
            $table->string('sourceNoOut');	
            $table->string('sourceIP');	
            $table->integer('elapsedTime');	
            $table->string('destNo');	
            $table->string('destNoOut');	
            $table->string('destIP');	
            $table->string('destName');	
            $table->integer('sourceIPValue');
            $table->integer('destIPValue');
            $table->integer('sourceIPFixed');
            $table->integer('destIPFixed');
            $table->integer('idxCustomer');	
            $table->integer('idxSupplier');	
            $table->integer('idxCustomerIP');	
            $table->integer('idxCustomerIPPrefix');	
            $table->string('destNoCustPrefix');	
            $table->string('destNoPrefix');	
            $table->string('destNoCust');	
            $table->string('destNoPrefixName');	
            $table->integer('idxSupplierIP');	
            $table->integer('idxSupplierIPPrefix');	
            $table->string('destNoSuppPrefix');	
            $table->string('destNoSupplier');	
            $table->string('destNoSupplierPrefix');	
            $table->string('destNoSupplierPrefixName');	
            $table->string('destNoRealPrefix');	
            $table->string('destNoRealPrefixName');	
            $table->decimal('custPrice', $precision = 15, $scale = 2);
            $table->decimal('supplierPrice', $precision = 15, $scale = 2);
            $table->integer('custTime');
            $table->integer('supplierTime');	
            $table->string('destIPOnly');	
            $table->string('sourceIPOnly');	
            $table->dateTime('created');	
            $table->dateTime('modified');	
            $table->integer('idxServer');	
            $table->string('reasonCode');	
            $table->integer('Expr1');	
            $table->string('nama');	
            $table->integer('idportal');	
            $table->boolean('active');
            $table->string('prefix');
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
        Schema::dropIfExists('vsummaries');
    }
};
