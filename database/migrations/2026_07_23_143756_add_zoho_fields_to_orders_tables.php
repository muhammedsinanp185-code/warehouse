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
        if (!Schema::hasColumn('sales_orders', 'reference_number')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->string('reference_number')->nullable()->after('so_number');
                $table->string('payment_terms')->nullable()->after('order_date');
                $table->timestamp('expected_shipment_date')->nullable()->after('payment_terms');
                $table->string('delivery_method')->nullable()->after('expected_shipment_date');
                $table->text('customer_notes')->nullable();
                $table->text('terms_conditions')->nullable();
            });
        }

        if (!Schema::hasColumn('sales_order_items', 'discount')) {
            Schema::table('sales_order_items', function (Blueprint $table) {
                $table->decimal('discount', 10, 2)->default(0)->after('unit_price');
                $table->decimal('tax', 5, 2)->default(0)->after('discount'); // Percentage
            });
        }

        if (!Schema::hasColumn('purchase_orders', 'reference_number')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->string('reference_number')->nullable()->after('po_number');
                $table->string('payment_terms')->nullable()->after('expected_date');
                $table->string('delivery_method')->nullable()->after('payment_terms');
                $table->text('vendor_notes')->nullable();
                $table->text('terms_conditions')->nullable();
            });
        }

        if (!Schema::hasColumn('purchase_order_items', 'unit_price')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
                $table->decimal('discount', 10, 2)->default(0)->after('unit_price');
                $table->decimal('tax', 5, 2)->default(0)->after('discount'); // Percentage
                $table->decimal('total', 12, 2)->default(0)->after('tax');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'payment_terms', 'expected_shipment_date', 'delivery_method', 'customer_notes', 'terms_conditions']);
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'payment_terms', 'delivery_method', 'vendor_notes', 'terms_conditions']);
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax']);
        });
    }
};
