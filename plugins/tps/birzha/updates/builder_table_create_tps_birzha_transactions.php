<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaTransactions extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_transactions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->decimal('amount', 10, 0)->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('transactable_id')->nullable();
            $table->string('transactable_type')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_transactions');
    }
}
