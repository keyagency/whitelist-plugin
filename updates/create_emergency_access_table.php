<?php namespace Key\Whitelist\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateEmergencyAccessTable extends Migration
{
    public function up()
    {
        Schema::create('key_whitelist_emergency_access', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'approved', 'denied', 'expired'])->default('pending');
            $table->timestamp('expires_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('ip_address');
            $table->index('token');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('key_whitelist_emergency_access');
    }
}
