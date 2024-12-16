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
        Schema::create('projects', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->id('projectID');
            $table->string('CID', 100);
            $table->string('projectName', 100);
            $table->string('custID', 100)->nullable();
            $table->string('contactName', 100);
            $table->string('email', 100);
            $table->string('telephone', 100);
            $table->string('address', 100);
            $table->string('detailProject1', 100);
            $table->string('detailProject2', 100);
            $table->string('statusData', 100);
            $table->string('createBy', 100);
            $table->string('modBy', 100);
            $table->string('dateCreated', 100);
            $table->string('dateMod', 100);
            $table->string('statusProject', 100);
            $table->string('isCLI', 100);
            $table->string('isFWT', 100);
            $table->string('isSIPTRUNK', 100);
            $table->string('isSIPREG', 100);
            $table->string('isApps', 100);
            $table->string('isSLI', 100);
            $table->string('startFT', 100);
            $table->string('endFT', 100);
            $table->string('startPT', 100);
            $table->string('endPT', 100);
            $table->string('startClient', 100);
            $table->string('approvedDate', 100)->nullable();
            $table->integer('approvedBy')->nullable();

            // ===========================
            $table->integer('detikAwal')->nullable();
            $table->integer('detikUnit')->nullable();
            
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
        Schema::dropIfExists('projects');
    }
};
