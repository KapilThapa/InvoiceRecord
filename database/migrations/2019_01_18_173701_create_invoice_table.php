<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bill_no')->unsigned();
            $table->string('customer_name')->nullable();
            $table->string('contact')->nullable();
            $table->integer('status_id');
            $table->string('notes')->nullable();
            $table->integer('bill_total')->unsigned();
            $table->integer('discount')->unsigned()->nullable();
            $table->string('discount_note')->nullable();
            $table->date('delivery_date');
            $table->date('full_payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
