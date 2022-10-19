<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServers extends Migration
{
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название сервера');
            $table->integer('type_server_id')->nullable()->comment('Тип сервера');
            $table->foreign('type_server_id')
                ->references('id')
                ->on('sys__type_server')
                ->onDelete('cascade');

            $table->text('icon')->default('default.webp')->comment('Фотография сервера');
            $table->string('version')->nullable()->comment('Версия сервера, если требуется');
            $table->string('dynmap')
                ->nullable()->comment('Ссылка на карту');
            $table->integer('max_online')->comment('Максимальный онлайн на сервере');

            $table->string('ip')->comment('IP Адрес сервера');
            $table->string('port')->comment('Порт сервера');
            $table->string('query_port')->comment('Query порт для получения данных');
            $table->string('rcon_port')->comment('Rcon порт для отправки сообщений');
            $table->string('rcon_password')->comment('Пароль для Rcon');
            $table->string('cluster')
                ->default('default')
                ->comment('На каком кластере находится сервер');

            $table->text('seo_title')->nullable()->comment('Титул для название сервера');
            $table->text('meta_description')->nullable()->comment('Текст для description');
            $table->text('meta_keywords')->nullable()->comment('Ключевые слова для страницы сервера');

            $table->text('body')->nullable()->comment('HTML описание сервера');
            $table->text('rules')->nullable()->comment('HTML правила сервера');

            $table->json('data')->nullable()
                ->comment('Данные которые нужно развернуть на странице сервера');
            $table->json('slide')->nullable()->comment('Слайд шоу, с фотографиями сервера');

            $table->integer('active')->default(0)->comment('Активный ли сервер');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
