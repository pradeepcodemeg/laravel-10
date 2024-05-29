<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['Admin', 'Trader', 'User'])->default('User');
            $table->string('name');
            $table->string('phone', 12)->unique();
            $table->string('email', 100)->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('store_name', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('region', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('street', 50)->nullable();
            $table->string('building_no', 11)->nullable();
            $table->string('postal_code', 11)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('device_token')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamp('email_verified_at')->nullable();
            // $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
