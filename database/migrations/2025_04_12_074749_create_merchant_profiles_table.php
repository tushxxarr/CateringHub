<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('merchant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->text('address');
            $table->string('phone');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchant_profiles');
    }
}
