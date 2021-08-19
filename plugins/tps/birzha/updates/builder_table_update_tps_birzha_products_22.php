<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProducts22 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->dropColumn('desc1');
            $table->dropColumn('desc2');
            $table->dropColumn('magl');
            $table->dropColumn('dfdfdf');
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_products', function($table)
        {
            $table->text('desc1')->nullable();
            $table->text('desc2')->nullable();
            $table->string('magl', 191)->nullable();
            $table->string('dfdfdf', 191)->nullable();
        });
    }
}
