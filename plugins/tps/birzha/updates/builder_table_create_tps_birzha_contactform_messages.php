<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaContactformMessages extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_contactform_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('mobile');
            $table->text('content');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_contactform_messages');
    }
}
