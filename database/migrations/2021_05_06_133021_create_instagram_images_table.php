<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateInstagramImagesTable
 */
class CreateInstagramImagesTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('instagram_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->string('media_id');
            $table->string('image_url');
            $table->string('filename')->nullable();
            $table->text('caption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_images');
    }
}
