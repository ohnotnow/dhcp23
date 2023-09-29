<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dhcp_entries', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address');
            $table->string('ip_address')->nullable();
            $table->string('hostname')->nullable();
            $table->string('added_by');
            $table->string('owner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhcp_entries');
    }
};
