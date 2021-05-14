<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('branch_id')->unsigned()->index();
            $table->decimal('basic_price', $precision = 13, $scale = 2);
            $table->decimal('additional_friday_price', $precision = 13, $scale = 2);
            $table->decimal('additional_saturday_price', $precision = 13, $scale = 2);
            $table->decimal('additional_sunday_price', $precision = 13, $scale = 2);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studios');
    }
}
