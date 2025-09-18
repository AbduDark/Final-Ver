
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['store_id', 'created_at']);
            $table->index('invoice_number');
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['store_id', 'quantity']);
            $table->index('code');
            $table->index('category_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->index(['invoice_id', 'product_id']);
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->index(['store_id', 'status']);
            $table->index('ticket_number');
        });

        Schema::table('treasury_transactions', function (Blueprint $table) {
            $table->index(['treasury_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['store_id', 'created_at']);
            $table->dropIndex(['invoice_number']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['store_id', 'quantity']);
            $table->dropIndex(['code']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropIndex(['invoice_id', 'product_id']);
        });

        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropIndex(['store_id', 'status']);
            $table->dropIndex(['ticket_number']);
        });

        Schema::table('treasury_transactions', function (Blueprint $table) {
            $table->dropIndex(['treasury_id', 'created_at']);
            $table->dropIndex(['type', 'created_at']);
        });
    }
};
