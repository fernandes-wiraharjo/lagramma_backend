<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('unique_code', 3)->nullable()->after('vendor_invoice_id');
            $table->string('proof_file')->nullable()->after('invoice_url');
            $table->string('payer_name')->nullable()->after('proof_file');
            $table->string('payer_account_number')->nullable()->after('payer_name');
            // Optionally normalize statuses: PENDING, UPLOADED, APPROVED, REJECTED
            $table->string('status')->default('PENDING')->change(); // if it's already string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn(['unique_code','proof_file','payer_name','payer_account_number']);
        });
    }
};
