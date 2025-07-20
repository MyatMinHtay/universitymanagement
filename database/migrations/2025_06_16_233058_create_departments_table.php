<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * //Parent Table 
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();//1
            $table->string('fullname');
            $table->string('shortname');
            $table->string('deptCode');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * CREDATE TABLE departments (id, fullname, shortname, deptCode, status, logo, banner, created_at, updated_at)
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
