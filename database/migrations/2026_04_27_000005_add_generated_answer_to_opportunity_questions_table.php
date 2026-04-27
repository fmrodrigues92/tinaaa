<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opportunity_questions', function (Blueprint $table) {
            $table->longText('generated_answer')->nullable()->after('context');
            $table->json('answer_citations')->nullable()->after('generated_answer');
            $table->string('answer_provider')->nullable()->after('answer_citations');
            $table->string('answer_model')->nullable()->after('answer_provider');
            $table->timestamp('answered_at')->nullable()->after('answer_model');
        });
    }

    public function down(): void
    {
        Schema::table('opportunity_questions', function (Blueprint $table) {
            $table->dropColumn([
                'generated_answer',
                'answer_citations',
                'answer_provider',
                'answer_model',
                'answered_at',
            ]);
        });
    }
};
