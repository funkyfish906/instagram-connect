<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateInstagramProfilesTable
 */
class CreateInstagramProfilesTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('instagram_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('user_id')->unique();
            $table->string('profile_picture')->nullable();
            $table->string('account_type');
            $table->string('access_token');
            $table->dateTime('token_expires_in');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_profiles');
    }
}
