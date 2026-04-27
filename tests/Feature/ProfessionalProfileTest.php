<?php

use App\Models\ProfessionalExperience;
use App\Models\ProfessionalProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('authenticated users can view their professional profile evidence', function () {
    $user = User::factory()->create();

    ProfessionalExperience::query()->create([
        'user_id' => $user->id,
        'role' => 'Senior Developer',
        'company' => 'TINAAA Labs',
        'technologies' => ['Laravel', 'React'],
    ]);

    $this->actingAs($user)
        ->get(route('professional-profile.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('professional-profile/index')
            ->has('experiences', 1)
            ->has('projects', 0)
        );
});

test('authenticated users can register professional experiences and projects', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('professional-profile.experiences.store'), [
            'role' => 'Backend Developer',
            'company' => 'Example Inc',
            'context' => 'Built internal tools.',
            'technologies' => 'Laravel, Postgres, Laravel',
        ])
        ->assertRedirect(route('professional-profile.index'));

    $experience = ProfessionalExperience::query()->firstOrFail();

    expect($experience)
        ->user_id->toBe($user->id)
        ->technologies->toBe(['Laravel', 'Postgres']);

    $this->actingAs($user)
        ->post(route('professional-profile.projects.store'), [
            'professional_experience_id' => $experience->id,
            'name' => 'Search platform',
            'problem' => 'Slow candidate matching.',
            'impact' => 'Reduced manual review.',
            'technologies' => 'React, Laravel',
        ])
        ->assertRedirect(route('professional-profile.index'));

    expect(ProfessionalProject::query()->count())->toBe(1);
});

test('users cannot attach projects to another user experience', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherExperience = ProfessionalExperience::query()->create([
        'user_id' => $otherUser->id,
        'role' => 'Developer',
        'company' => 'Other Inc',
    ]);

    $this->actingAs($user)
        ->post(route('professional-profile.projects.store'), [
            'professional_experience_id' => $otherExperience->id,
            'name' => 'Should fail',
        ])
        ->assertNotFound();
});
