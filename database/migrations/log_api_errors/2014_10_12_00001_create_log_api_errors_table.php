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
    Schema::create('log_api_errors', function (Blueprint $table) {
      $table->id();
      $table->string('name', 150);
      $table->text('url');
      $table->string('method', 50);
      $table->text('header')->nullable();
      $table->text('request_param')->nullable();
      $table->text('request_body')->nullable();
      $table->string('status_code', 10)->nullable();
      $table->text('response')->nullable();
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();

      // Define foreign key constraints
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
    Schema::dropIfExists('log_api_errors');
  }
};
