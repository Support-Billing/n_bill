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
        Schema::create('customers', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->id('clientID');
            $table->string('QID', 100)->nullable();
            $table->string('clientName', 100)->nullable();
            $table->string('clientName2', 100)->nullable();
            $table->string('contactName', 100)->nullable();
            $table->string('contactName2', 100)->nullable();
            $table->string('telephone1', 100)->nullable();
            $table->string('telephone2', 100)->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('email1', 100)->nullable();
            $table->string('email2', 100)->nullable();
            $table->string('otherDetails', 100)->nullable();
            $table->string('custStatus', 100)->nullable();
            $table->string('priority', 100)->nullable();
            $table->string('submitDate', 100)->nullable();
            $table->string('prospectDate', 100)->nullable();
            $table->string('statusData', 100)->nullable();
            $table->string('createBy', 100)->nullable();
            $table->string('modBy', 100)->nullable();
            $table->string('dateCreated', 100)->nullable();
            $table->string('dateMod', 100)->nullable();
            $table->string('marketingID', 100)->nullable();
            $table->string('marketingID2', 100)->nullable();
            $table->string('taxID', 100)->nullable();
            $table->string('taxAddress', 100)->nullable();
            $table->string('leads', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('invoicePrior', 100)->nullable();
            $table->string('isCustom', 100)->nullable();
            $table->string('isInv', 100)->nullable();
            $table->string('isTier', 100)->nullable();
            $table->string('customText', 100)->nullable();

            // ===========================
            $table->integer('detikAwal')->nullable();
            $table->integer('detikUnit')->nullable();
            $table->double('mincomm', 15, 2);
            $table->integer('idportal')->nullable();

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
        Schema::dropIfExists('customers');
    }
};
