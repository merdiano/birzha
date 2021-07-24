<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts13 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->integer('measure_id')->nullable()->change();
            $table->decimal('price', 10, 0)->nullable()->change();
            $table->integer('currency_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->integer('measure_id')->nullable(false)->change();
            $table->decimal('price', 10, 0)->nullable(false)->change();
            $table->integer('currency_id')->nullable(false)->change();
        });
    }
}
