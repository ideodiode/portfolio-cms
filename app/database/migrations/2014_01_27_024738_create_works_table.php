<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('works', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title')->unique();
			$table->text('lg_description');
			// img_order in format /int/int/int/int where int = $imageID
			$table->string('img_order');
			// featured_img & thumbnail_img not managed by images table
			$table->string('featured_filepath')->nullable();
			$table->string('thumbnail_filepath');
			$table->string('thumbnail2x_filepath');
			$table->boolean('featured')->default(false);
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
		Schema::drop('works');
	}

}
