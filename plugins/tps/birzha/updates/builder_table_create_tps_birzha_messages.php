<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzha extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->integer('sender_id');
            $table->integer('reciver_id');
            $table->dateTime('send_at');
            $table->dateTime('read_at')->nullable();
            $table->text('message');
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_');
    }
}
