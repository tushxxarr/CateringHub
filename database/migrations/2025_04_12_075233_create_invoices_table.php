<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('merchant_id')->constrained('merchant_profiles')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
