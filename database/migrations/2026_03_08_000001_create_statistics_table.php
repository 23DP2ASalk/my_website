<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('sport_type'); // Basketball, Football, Volleyball
            $table->date('game_date');
            
            // Basketball specific stats
            $table->integer('points')->nullable();
            $table->integer('assists')->nullable();
            $table->integer('rebounds')->nullable();
            $table->integer('steals')->nullable();
            $table->integer('blocks')->nullable();
            
            // Football specific stats
            $table->integer('goals')->nullable();
            $table->integer('assists_football')->nullable();
            $table->integer('shots')->nullable();
            $table->integer('passes')->nullable();
            $table->integer('tackles')->nullable();
            
            // Volleyball specific stats
            $table->integer('spikes')->nullable();
            $table->integer('blocks_volleyball')->nullable();
            $table->integer('serves')->nullable();
            $table->integer('digs')->nullable();
            $table->integer('aces')->nullable();
            
            // Common fields
            $table->integer('minutes_played')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('sport_type');
            $table->index('game_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistics');
    }
};
