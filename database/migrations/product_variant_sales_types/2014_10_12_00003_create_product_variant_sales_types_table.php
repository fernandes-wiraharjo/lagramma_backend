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
    Schema::create('product_variant_sales_types', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('id_product_variant');
      $table->unsignedBigInteger('id_sales_type');
      $table->decimal('price', 15, 2)->nullable();
      $table->boolean('is_default')->nullable();
      $table->boolean('is_active')->default(true);
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();

      // Define foreign key constraints
      $table
        ->foreign('id_product_variant')
        ->references('id')
        ->on('product_variants')
        ->onDelete('restrict');
      $table
        ->foreign('id_sales_type')
        ->references('id')
        ->on('sales_types')
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
    Schema::dropIfExists('product_variant_sales_types');
  }
};
