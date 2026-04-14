<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('type');
            $table->index(['type', 'entity_id']);
            $table->unique(['employee_id', 'type', 'entity_id'], 'subscription_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_subscriptions');
    }
};
