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
        Schema::create('sfs_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('SFS_set_id')->constrained();
            $table->bigInteger('product_id');
            $table->tinyInteger('always_added');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SFS_product');
    }
};
