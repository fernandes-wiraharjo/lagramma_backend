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
    Schema::create('order_modifiers', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('modifier_id');
      $table->unsignedBigInteger('modifier_option_id');
      $table->string('modifier_name', 50);
      $table->string('modifier_option_name', 50);
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
        ->foreign('modifier_id')
        ->references('id')
        ->on('modifiers')
        ->onDelete('restrict');
    $table
        ->foreign('modfier_option_id')
        ->references('id')
        ->on('modifier_options')
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
    Schema::dropIfExists('order_modifiers');
  }
};
