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
        Schema::create('SFS_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('active');
            $table->integer('quantity');
            $table->tinyInteger('display_in_checkout');
            $table->tinyInteger('repeatable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SFS_sets');
    }
};