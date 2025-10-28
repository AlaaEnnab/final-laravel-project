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
    Schema::table('products', function (Blueprint $table) {
        // عمود قابل لأن يكون فارغاً لضمان عدم كسر البيانات الحالية
        $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        // إزالة القيد والعمود عند التراجع
        $table->dropForeign(['vendor_id']);
        $table->dropColumn('vendor_id');
    });
}

};
