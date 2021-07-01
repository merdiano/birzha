<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaCurrencies extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_currency', 'tps_birzha_currencies');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_currencies', 'tps_birzha_currency');
    }
}
