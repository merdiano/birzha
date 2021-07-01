<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaCountries extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_countries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('code')->nullable();
            $table->string('name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_countries');
    }
}
