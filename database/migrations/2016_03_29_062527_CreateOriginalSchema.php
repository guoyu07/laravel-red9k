<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginalSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
			$table->string('address');
            $table->boolean('banned');
			$table->boolean('admin');
            $table->rememberToken();
            $table->timestamps();
        });
		Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
		Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
			$table->string('title');
			$table->string('url');
			$table->string('category');
			$table->integer('user_id')->index();
			$table->integer('voteCount');
        });
		Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
			$table->string('address');
			$table->integer('post_id')->index();
        });
		Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('text');
            $table->integer('post_id')->index();
            $table->integer('user_id')->index();
			$table->integer('comment_id')->index();
			$table->integer('comment_vote_id')->index();
            $table->integer('voteCount');
        });
		Schema::create('comment_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('address');
            $table->integer('comment_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
		Schema::drop('password_resets');
		Schema::drop('posts');
		Schema::drop('votes');
		Schema::drop('comments');
		Schema::drop('comment_votes');
    }
}
