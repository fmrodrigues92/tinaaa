<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->string('company');
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('context')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('results')->nullable();
            $table->json('technologies')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'started_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_experiences');
    }
};
