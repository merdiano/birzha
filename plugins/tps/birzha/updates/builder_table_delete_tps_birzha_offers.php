<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteTpsBirzhaOffers extends Migration
{
    public function up()
    {
        Schema::dropIfExists('tps_birzha_offers');
    }
    
    public function down()
    {
        Schema::create('tps_birzha_offers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('product_id');
            $table->integer('vendor_id');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('status', 10);
            $table->integer('measure_id');
            $table->integer('payment_term_id')->nullable();
            $table->integer('delivery_term_id')->nullable();
            $table->string('packaging', 191)->nullable();
            $table->string('place', 191)->nullable();
            $table->string('mark', 191)->nullable();
            $table->text('status_note')->nullable();
            $table->integer('payment_id')->nullable();
            $table->string('name', 191);
            $table->integer('currency_id');
            $table->dateTime('ends_at')->nullable();
            $table->string('manufacturer', 191)->nullable();
            $table->integer('country_id')->nullable();
        });
    }
}
