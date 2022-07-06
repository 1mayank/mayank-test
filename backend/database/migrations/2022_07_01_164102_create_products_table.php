<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('isbn13', 100);
            $table->string('title', 255);
            $table->timestamp('publication_date')->nullable();
            $table->text('authors')->nullable();
            $table->text('category')->nullable();
            $table->string('concept', 255)->nullable();
            $table->string('language', 50)->nullable();
            $table->string('language_version', 10)->nullable();
            $table->string('tool', 70)->nullable();
            $table->string('vendor', 50)->nullable();
            $table->text('prices')->nullable();
            $table->string('cover_image', 255)->nullable();
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
        Schema::dropIfExists('products');
    }
}
