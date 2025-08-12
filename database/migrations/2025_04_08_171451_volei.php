<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar 'times' primeiro (sem FKs)
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('partidas_win')->default(0);
            $table->integer('partidas_lose')->default(0);
            $table->integer('set_win')->default(0);
            $table->integer('set_lose')->default(0);
            $table->integer('pontos')->default(0);
            $table->string('foto')->nullable();
            $table->integer('pontos_win')->default(0);
            $table->integer('pontos_lose')->default(0);
            
            $table->timestamps();
        });

        // 2. Criar 'jogadores' com FK para 'times'
        Schema::create('jogadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_id_time');
            $table->string('nome');
            $table->integer('idade')->nullable();
            $table->string('posicao')->nullable(); 
            $table->text('status')->nullable(); // Titular/Reserva
            $table->integer('mvp')->default(0);
            $table->integer('altura')->nullable();
            $table->integer('peso');
            $table->timestamps();

            $table->foreign('fk_id_time')->references('id')->on('times')->onDelete('cascade');
        });

        // 3. Adicionar opcionalmente um jogador responsável em 'times' (após criar jogadores)
        Schema::table('times', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_id_player')->nullable()->after('nome');
            $table->foreign('fk_id_player')->references('id')->on('jogadores')->onDelete('set null');
        });

        // 4. Criar 'partidas'
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_id_time1');
            $table->unsignedBigInteger('fk_id_time2');
            $table->string('placar_geral')->nullable();        
            $table->timestamps();

            $table->foreign('fk_id_time1')->references('id')->on('times')->onDelete('cascade');
            $table->foreign('fk_id_time2')->references('id')->on('times')->onDelete('cascade');
        });

        // 5. Criar 'sets'
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_id_partida')->nullable();
            $table->unsignedBigInteger('vencedor_id')->nullable();
        
            // Pontuação de cada set para cada time
            $table->integer('set1_time1')->default(0);
            $table->integer('set1_time2')->default(0);
            $table->integer('set2_time1')->default(0);
            $table->integer('set2_time2')->default(0);
            $table->integer('set3_time1')->default(0);
            $table->integer('set3_time2')->default(0);
        
            $table->timestamps();
        
            $table->foreign('fk_id_partida')->references('id')->on('partidas')->onDelete('cascade');
            $table->foreign('vencedor_id')->references('id')->on('times')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Remover as FKs primeiro para evitar erros
        Schema::table('sets', function (Blueprint $table) {
            $table->dropForeign(['fk_id_partida']);
            $table->dropForeign(['vencedor_id']);
        });

        Schema::table('partidas', function (Blueprint $table) {
            $table->dropForeign(['fk_id_time1']);
            $table->dropForeign(['fk_id_time2']);
        });

        Schema::table('times', function (Blueprint $table) {
            $table->dropForeign(['fk_id_player']);
        });

        Schema::table('jogadores', function (Blueprint $table) {
            $table->dropForeign(['fk_id_time']);
        });

        // Agora sim, pode apagar as tabelas
        Schema::dropIfExists('sets');
        Schema::dropIfExists('partidas');
        Schema::dropIfExists('jogadores');
        Schema::dropIfExists('times');
    }
};
