<?php

use App\Models\Opportunity;
use App\Models\OpportunityQuestion;
use App\Models\ProfessionalExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('authenticated users can view opportunities', function () {
    $user = User::factory()->create();

    Opportunity::query()->create([
        'user_id' => $user->id,
        'title' => 'Full Stack Developer',
        'company' => 'TINAAA Labs',
    ]);

    $this->actingAs($user)
        ->get(route('opportunities.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('opportunities/index')
            ->has('opportunities', 1)
        );
});

test('authenticated users can register opportunities and questions', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('opportunities.store'), [
            'title' => 'Laravel Engineer',
            'company' => 'Example Inc',
            'status' => 'active',
            'offered_salary' => 'R$ 12.000 CLT',
            'expected_salary' => 'R$ 14.000 CLT',
            'description' => 'Build Laravel products.',
            'requirements' => 'Laravel, React, Postgres',
        ])
        ->assertRedirect(route('opportunities.index'));

    $opportunity = Opportunity::query()->firstOrFail();

    expect($opportunity)
        ->offered_salary->toBe('R$ 12.000 CLT')
        ->expected_salary->toBe('R$ 14.000 CLT');

    $this->actingAs($user)
        ->post(route('opportunities.questions.store', $opportunity), [
            'question' => 'Tell us about a Laravel project.',
            'context' => 'Application form',
        ])
        ->assertRedirect(route('opportunities.index'));

    expect(OpportunityQuestion::query()->count())->toBe(1);
});

test('authenticated users can generate grounded answers for opportunity questions', function () {
    config(['tinaaa.ai.provider' => 'extractive']);

    $user = User::factory()->create();
    $opportunity = Opportunity::query()->create([
        'user_id' => $user->id,
        'title' => 'Laravel Engineer',
        'requirements' => 'Laravel, React, Postgres',
    ]);
    $question = $opportunity->questions()->create([
        'question' => 'Tell us about a Laravel project.',
        'status' => 'pending',
    ]);

    ProfessionalExperience::query()->create([
        'user_id' => $user->id,
        'role' => 'Senior PHP Software Engineer',
        'company' => 'Allycode',
        'context' => 'Built full stack products with Laravel and Vue.',
        'results' => 'Led delivery and supported junior developers.',
        'technologies' => ['Laravel', 'Vue.js', 'PHP'],
    ]);

    $this->actingAs($user)
        ->post(route('opportunities.questions.answer', $question))
        ->assertRedirect(route('opportunities.index'));

    $question->refresh();

    expect($question)
        ->status->toBe('answered')
        ->generated_answer->not->toBeNull()
        ->answer_provider->toBe('extractive')
        ->answer_citations->not->toBeEmpty();
});

test('users cannot add questions to another user opportunity', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $opportunity = Opportunity::query()->create([
        'user_id' => $otherUser->id,
        'title' => 'Private opportunity',
    ]);

    $this->actingAs($user)
        ->post(route('opportunities.questions.store', $opportunity), [
            'question' => 'Should fail',
        ])
        ->assertNotFound();
});
