<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignId('professional_experience_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->text('impact')->nullable();
            $table->text('technical_decisions')->nullable();
            $table->json('technologies')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_projects');
    }
};
