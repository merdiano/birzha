<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaMeasure extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_measure', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_measure');
    }
}
