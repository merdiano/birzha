<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaCurrency extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_currency', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_currency');
    }
}
