<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('company')->nullable();
            $table->string('seniority')->nullable();
            $table->string('offered_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('status')->default('draft');
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
