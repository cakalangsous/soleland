<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id');
            $table->string('username');
            $table->string('password');
            $table->string('salt');
            $table->string('name')->nullable()->default(null);
            $table->string('gender')->nullable()->default(null);
            $table->integer('level')->default(0);
            $table->integer('experience')->default(0);
            $table->timestamp('last_login')->nullable()->default(null);
            $table->double('x_coordinate')->nullable()->default(null);
            $table->double('y_coordinate')->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('characters');
    }
};
