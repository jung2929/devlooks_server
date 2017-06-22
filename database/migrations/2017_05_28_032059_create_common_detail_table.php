<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_detail', function (Blueprint $table) {
	    $table->string('code', 2);
	    $table->string('code_detail', 3);
	    $table->primary(['code', 'code_detail']);
	    $table->string('code_detail_name', 20);
            $table->timestamps();
        });
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '001',
		'code_detail_name' => 'Android',
	]);
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '002',
		'code_detail_name' => 'Java',
	]);
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '003',
		'code_detail_name' => 'Python',
	]);
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '004',
		'code_detail_name' => 'PHP',
	]);
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '005',
		'code_detail_name' => 'JavaScript',
	]);
	\App\CommonDetail::create([
		'code' => 'AA',
		'code_detail' => '006',
		'code_detail_name' => 'ETC',
	]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_detail');
    }
}
