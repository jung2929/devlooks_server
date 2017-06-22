<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common', function (Blueprint $table) {
            $table->string('code', 2);
	    $table->primary('code');
	    $table->string('code_name', 20);
            $table->timestamps();
        });
	\App\Common::create([
		'code' => 'AA',
		'code_name' => '카테고리명',
	]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common');
    }
}
