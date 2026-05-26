<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('full_name')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamp('phone')->nullable();
            $table->enum('role', ['admin', 'pelanggan'])->default('pelanggan');
            $table->string('password');
            $table->timestamps();
        });
    }
};