<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites_content', function (Blueprint $table) {
		$table->string('email', 30);
		$table->string('password', 60);
		$table->string('content_title', 191);
		$table->string('content_url', 191);
		$table->primary(['email', 'password', 'content_url']);
		$table->string('content_tag', 191);
		$table->string('content_summary', 191);
		$table->string('content_watch_count', 10);
		$table->string('content_favorites_count', 10);
            	$table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites_content');
    }
}
