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
    Schema::table('order_deliveries', function (Blueprint $table) {
        $table->string('receipt_number', 100)->after('sto_note');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('order_deliveries', function (Blueprint $table) {
        // Drop dropping column
        $table->dropColumn('receipt_number');
    });
  }
};
