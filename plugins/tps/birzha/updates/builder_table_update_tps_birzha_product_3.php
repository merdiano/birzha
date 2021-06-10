<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaProduct3 extends Migration
{
    public function up()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->string('status', 191)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('tps_birzha_product', function($table)
        {
            $table->smallInteger('status')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
