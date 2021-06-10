<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaOffer extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_offer', function($table)
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
            $table->smallInteger('status');
            $table->integer('measure_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_offer');
    }
}
