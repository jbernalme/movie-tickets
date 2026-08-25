<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_id')->nullable()->after('status');
            $table->string('gateway_payment_id')->nullable()->after('gateway_id');
            $table->string('currency', 3)->default('cop')->after('gateway_payment_id');
            $table->json('metadata')->nullable()->after('currency');
            $table->string('failure_reason')->nullable()->after('metadata');
            $table->string('payment_method')->nullable()->change();
            $table->string('transaction_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['gateway_id', 'gateway_payment_id', 'currency', 'metadata', 'failure_reason']);
            $table->string('payment_method')->nullable(false)->change();
            $table->string('transaction_id')->nullable(false)->change();
        });
    }
};
