<?php namespace TPS\Birzha\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateTpsBirzhaPayments extends Migration
{
    public function up()
    {
        Schema::rename('tps_birzha_payment', 'tps_birzha_payments');
    }
    
    public function down()
    {
        Schema::rename('tps_birzha_payments', 'tps_birzha_payment');
    }
}
