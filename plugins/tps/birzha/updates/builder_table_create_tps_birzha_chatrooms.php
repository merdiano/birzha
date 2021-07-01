<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaChatrooms extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_chatrooms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_chatrooms');
    }
}
