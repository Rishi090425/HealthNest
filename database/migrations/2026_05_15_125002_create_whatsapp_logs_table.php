<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_logs', function (Blueprint $row) {
            $row->id();
            $row->string('to');
            $row->text('message');
            $row->string('status')->default('sent'); // sent, failed, pending
            $row->string('provider')->default('simulated');
            $row->string('transaction_id')->nullable();
            $row->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
