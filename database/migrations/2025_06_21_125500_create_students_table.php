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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('year');
            $table->string('roll_number');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('image');
            $table->unsignedBigInteger('department_id');//1
            $table->foreign('department_id')->references('id')->on('departments');
            $table->timestamps();

             // Composite unique constraint
            $table->unique(['year', 'roll_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
