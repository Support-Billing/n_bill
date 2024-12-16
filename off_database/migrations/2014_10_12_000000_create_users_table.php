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
        Schema::create('users', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            // $table->string("id", 100)->nullable(false)->primary();
            // $table->string("id_role", 100);
            // $table->string("id_employee", 100);
            $table->uuid('id')->primary();
            $table->foreignUuid('id_employee');
            $table->foreignUuid('id_role');
            // $table->foreignUuid('id_employee')->constrained(
            //     table: 'employees', indexName: 'users_id_employee'
            // );
            // $table->foreignUuid('id_role')->constrained(
            //     table: 'roles', indexName: 'users_id_roles'
            // );
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum("status", ["ACTIVE", "INACTIVE"]);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
