<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing BIGINT ID
            $table->string('name'); // Name of the item
            $table->foreignId('item_list_id')->constrained('item_lists')->onDelete('cascade'); // Foreign key to item_lists
            $table->boolean('crossed_out')->default(0); // Boolean field for crossed_out status
            $table->timestamps(); // Created_at and updated_at timestamps

            // Add unique constraint on name and item_list_id
            $table->unique(['name', 'item_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
