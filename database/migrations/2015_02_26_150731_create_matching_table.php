<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('matching', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('godfather_id')->unsigned();
			$table->integer('protege_id')->unsigned();
			$table->integer('match_batch')->default(0);
			$table->boolean('match_code')->default(false);
			$table->timestamps();

			$table->foreign('godfather_id')
			->references('id')->on('users')
			->onDelete('cascade');
			$table->foreign('protege_id')
			->references('id')->on('users')
			->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('matching', function(Blueprint $table)
		{
			//
		});
	}

}
