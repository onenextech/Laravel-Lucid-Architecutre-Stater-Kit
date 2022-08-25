<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_services', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->longText('description')->nullable();
            $table->boolean('force_required')->default(false)
                ->comment('Define if a service is required as a core service, if true -> can\'t be turn off');
            $table->boolean('active')->default(true)
                ->comment('Define application service should be registered');
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
        Schema::dropIfExists('application_services');
    }
};
