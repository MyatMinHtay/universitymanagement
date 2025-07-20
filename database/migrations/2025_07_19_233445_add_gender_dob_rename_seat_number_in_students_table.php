<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add new fields only if they don't exist
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('name');
            }
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
        });
        
        // Rename seat_number to roll_number using raw SQL only if seat_number exists
        if (Schema::hasColumn('students', 'seat_number')) {
            DB::statement('ALTER TABLE students CHANGE seat_number roll_number VARCHAR(255)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn(['gender', 'date_of_birth']);
        });
        
        // Rename back roll_number to seat_number using raw SQL
        DB::statement('ALTER TABLE students CHANGE roll_number seat_number VARCHAR(255)');
    }
};
