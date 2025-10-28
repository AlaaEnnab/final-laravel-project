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
        Schema::table('orders', function (Blueprint $table) {
         
            $table->decimal('items_total', 10, 2)->default(0);
$table->decimal('delivery_fee', 10, 2)->default(0);
$table->decimal('grand_total', 10, 2)->default(0);
$table->string('payment_method')->nullable(); // cod, card, etc.
$table->string('payment_status')->default('pending'); // pending, paid...
$table->string('payment_provider_id')->nullable();
$table->json('items')->nullable(); // المنتجات بصيغة JSON

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
           $table->dropColumn([
    'items_total',
    'delivery_fee',
    'grand_total',
    'payment_method',
    'payment_status',
    'payment_provider_id',
    'items',
]);

        });
    }
};
