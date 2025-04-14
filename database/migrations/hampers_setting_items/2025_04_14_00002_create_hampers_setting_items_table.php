<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('hampers_setting_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('hampers_setting_id');
      $table->unsignedBigInteger('product_id');
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();

      // Define foreign key constraints
      $table
        ->foreign('hampers_setting_id')
        ->references('id')
        ->on('hampers_settings')
        ->onDelete('restrict');
      $table
        ->foreign('product_id')
        ->references('id')
        ->on('products')
        ->onDelete('restrict');
      $table
        ->foreign('created_by')
        ->references('id')
        ->on('users')
        ->onDelete('restrict');
      $table
        ->foreign('updated_by')
        ->references('id')
        ->on('users')
        ->onDelete('restrict');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hampers_setting_items');
  }
};
