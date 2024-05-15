<?php

use App\Constants\GlobalConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_approver_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->enum('status', GlobalConstant::STATUS)->default(GlobalConstant::STATUS_PENDING);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_approver_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->index('user_approver_id');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('no action')->onUpdate('cascade');
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_approvals');
    }
}
