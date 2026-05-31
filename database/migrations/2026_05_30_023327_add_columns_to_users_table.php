<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rol')->default('cliente')->after('password');
            $table->string('dui')->nullable()->after('rol');
            $table->string('telefono')->nullable()->after('dui');
            $table->string('direccion')->nullable()->after('telefono');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'dui', 'telefono', 'direccion']);
        });
    }
};