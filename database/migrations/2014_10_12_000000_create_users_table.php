<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // migraacines de la base de datos
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('tipoUsuario');
            $table->string('name');

              //parte modificada
             
              $table->string('lastname');
              $table->string('secondlastname');
              $table->string('grado');
  
              //termina parte modificada
              
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('validado');

            $table->string('api_token', 80)->aunique()->nullable()->default(null);

            $table->rememberToken();
            $table->timestamps();
            $table->string('token_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
