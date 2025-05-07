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
    Schema::create('order_delivery_details', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_delivery_id');
      $table->decimal('weight', 8, 2); // gram
      $table->decimal('width', 8, 2); // cm
      $table->decimal('height', 8, 2); // cm
      $table->decimal('length', 8, 2); // cm
      $table->unsignedBigInteger('created_by');
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();

    // Define foreign key constraints
    $table
        ->foreign('order_delivery_id')
        ->references('id')
        ->on('order_deliveries')
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
    Schema::dropIfExists('order_delivery_details');
  }
};
