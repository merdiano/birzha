<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaProduct extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_product', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->smallInteger('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_product');
    }
}
