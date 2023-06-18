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
        Schema::create('office_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->nullable();
            // $table->unsignedBigInteger('total_record_id')->default(0)->nullable();
            $table->string('ip_address');
            $table->string('workplace');
            $table->time('visit_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->integer('total_physical_duration')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_records');
    }
};
