<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddProducts extends Migration
{
    private $table = 'products';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));;
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->integer('price', false, true);
            $table->string('type', 100);
            $table->string('color', 30);
            // Probably we should use Enum or Int but it's ok to use varchar as initial implementation
            $table->string('size', 4);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
