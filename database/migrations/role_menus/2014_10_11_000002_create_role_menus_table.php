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
    Schema::create('role_menus', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('role_id');
      $table->unsignedBigInteger('menu_id');
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
      $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
      $table->foreign('menu_id')->references('id')->on('menus')->onDelete('restrict');

      // Add unique constraint to prevent duplicates
      $table->unique(['role_id', 'menu_id'], 'unique_role_menu');

      // Add index to optimize queries filtering by role_id and menu_id
      $table->index(['role_id', 'menu_id'], 'idx_role_menu');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('role_menus', function (Blueprint $table) {
        $table->dropUnique('unique_role_menu'); // Drop the unique constraint
        $table->dropIndex('idx_role_menu'); // Drop the index
    });

    Schema::dropIfExists('role_menus');
  }
};
