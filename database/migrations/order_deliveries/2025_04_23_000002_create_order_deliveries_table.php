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
    Schema::create('order_deliveries', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('order_delivery_id')->nullable(); //raja ongkir order id
      $table->string('order_delivery_no', 50)->nullable(); //raja ongkir order no
      $table->unsignedBigInteger('address_id');
      $table->timestamp('date');
      $table->string('shipping_name', 50);
      $table->string('shipping_type', 30);
      $table->decimal('shipping_cost', 15, 2);
      $table->decimal('shipping_cashback', 15, 2);
      $table->decimal('service_fee', 15, 2);
      $table->decimal('grand_total', 15, 2);
      $table->boolean('is_send_to_other')->default(false);
      $table->string('sto_pic_name', 150);
      $table->string('sto_pic_phone', 30);
      $table->string('sto_receiver_name', 150);
      $table->string('sto_receiver_phone', 150);
      $table->text('sto_note');
      $table->string('status', 50);
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
        ->foreign('address_id')
        ->references('id')
        ->on('user_addresses')
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
    Schema::dropIfExists('order_deliveries');
  }
};
