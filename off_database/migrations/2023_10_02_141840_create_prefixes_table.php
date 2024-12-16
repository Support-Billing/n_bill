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
        Schema::create('prefixes', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->id('prefixID'); // idx
            $table->string('projectID', 100);
            $table->string('prefixNumber', 100);
            $table->string('notes', 100)->nullable();
            $table->string('flagOld', 100)->nullable(); // idx
            $table->string('statusData', 100); // active
            $table->string('createBy', 100);
            $table->string('createDate', 100);
            $table->string('modBy', 100)->nullable(); // ini username pindah ke created_by
            $table->string('modDate', 100)->nullable(); // ini di bikinnya created

            //=============================
            $table->string('prefixName', 100)->nullable();
            $table->string('prefixLength', 100)->nullable();
            $table->foreignId('idxPrefixGroup');

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
        Schema::dropIfExists('prefixes');
    }
};
