<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryAndStatusToOrders extends Migration
{
    private $table = 'orders';

    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->string('country', 2)->nullable();
            $table->integer('status')->default(1);
        });
    }

    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('status');
        });
    }
}
