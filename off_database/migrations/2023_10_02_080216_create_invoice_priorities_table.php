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
        // Schema::create('invoice_priorities', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name', 100);
        //     $table->integer('created_by')->nullable();
        //     $table->integer('updated_by')->nullable();
        //     $table->integer('deleted_by')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('invoice_priorities');
    }
};
