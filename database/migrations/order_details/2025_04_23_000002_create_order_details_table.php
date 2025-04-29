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
    Schema::create('order_details', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('product_id');
      $table->unsignedBigInteger('product_variant_id');
      $table->string('type', 20);
      $table->string('product_name', 250);
      $table->string('product_variant_name', 250);
      $table->smallInteger('quantity');
      $table->decimal('price', 15, 2);
      $table->decimal('total_price', 15, 2);
      $table->unsignedBigInteger('created_by');
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();

    // Define foreign key constraints
    $table
        ->foreign('order_id')
        ->references('id')
        ->on('orders')
        ->onDelete('restrict');
    $table
        ->foreign('product_id')
        ->references('id')
        ->on('products')
        ->onDelete('restrict');
    $table
        ->foreign('product_variant_id')
        ->references('id')
        ->on('product_variants')
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
    Schema::dropIfExists('order_details');
  }
};
