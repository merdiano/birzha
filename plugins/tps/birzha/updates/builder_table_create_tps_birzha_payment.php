<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaPayment extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_payment', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('user_id');
            $table->decimal('amount', 10, 0);
            $table->string('payment_type');
            $table->text('note')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_payment');
    }
}
