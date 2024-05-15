<?php

use App\Constants\GlobalConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_driver_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->date('date');
            $table->enum('status', GlobalConstant::STATUS)->default(GlobalConstant::STATUS_PENDING);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_driver_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->index('user_driver_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('no action')->onUpdate('cascade');
            $table->index('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
