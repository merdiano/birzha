<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateTpsBirzhaExchangeRequests extends Migration
{
    public function up()
    {
        Schema::create('tps_birzha_exchange_requests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('payed_for_request', 10, 2);
            $table->integer('vendor_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('content')->nullable();
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tps_birzha_exchange_requests');
    }
}
