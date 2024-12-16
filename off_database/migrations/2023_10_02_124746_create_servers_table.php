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
        Schema::create('servers', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->id('serverID'); //idx
            $table->string('serverName', 100);
            $table->string('serverTitle', 100);
            $table->string('serverIP', 100);
            $table->string('serverType', 100);
            $table->string('notes', 100);
            $table->string('statusData', 100); // active
            $table->string('createBy', 100);
            $table->string('createDate', 100);
            $table->string('modBy', 100);
            $table->string('modDate', 100);
            
            // ========================================
            $table->string('serverPort', 100);
            $table->string('serverProtocol', 100);
            $table->string('serverUsername', 100);
            $table->string('serverPassword', 100);
            $table->string('serverSSH', 100);
            $table->string('serverVPNName', 100);
            $table->string('serverVPNIPAddress', 100);
            $table->string('serverVPNUsername', 100);
            $table->string('serverVPNPassword', 100);


            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps(); // created & modified
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
