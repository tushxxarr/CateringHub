<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('customer_profiles')->onDelete('cascade');
            $table->foreignId('merchant_id')->references('id')->on('merchant_profiles')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->text('delivery_address');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
