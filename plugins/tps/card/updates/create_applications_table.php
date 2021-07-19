<?php namespace TPS\CardApplication\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('tps_card_applications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('passport_id')->nullable();
            $table->date('passport_date')->nullable();
            $table->string('passport_by')->nullable();
            $table->string('address')->nullable();
            $table->string('work')->nullable();
            $table->string('passport_scan')->nullable();
            $table->string('orderId')->nullable();
//            $table->string('order_reference')->nullable();
            $table->boolean('payed')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tps_card_applications');
    }
}
