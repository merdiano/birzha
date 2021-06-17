<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaSliders extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_sliders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('code');
            $table->text('slide_items');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_sliders');
    }
}
