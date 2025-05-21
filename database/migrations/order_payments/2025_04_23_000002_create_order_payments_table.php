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
    Schema::create('order_payments', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->string('vendor_invoice_id', 100);
      $table->timestamp('transaction_date')->nullable();
      $table->string('status', 50); //PENDING, EXPIRED, PAID, SETTLED, FAILED
      $table->text('invoice_url');
      $table->string('payment_id', 50)->nullable();
      $table->string('payment_method', 50)->nullable();
      $table->string('bank_code', 50)->nullable();
      $table->string('payment_channel', 50)->nullable();
      $table->string('payment_destination', 50)->nullable();
      $table->timestamp('paid_at')->nullable();
      $table->timestamp('expiry_date')->nullable();
      $table->string('webhook_id', 50)->unique()->nullable();
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
    Schema::dropIfExists('order_payments');
  }
};
