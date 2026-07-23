<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->nullableMorphs('document'); // Adds document_type and document_id
            $table->dropColumn('reference_party');
        });
        
        // Modify type column to varchar to allow more types
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type VARCHAR(50)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropMorphs('document');
            $table->string('reference_party')->nullable();
        });
        
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type ENUM('in', 'out')");
    }
};
