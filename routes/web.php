<?php

use App\Src\Dashboard\Presentation\DashboardController;
use App\Src\Opportunity\Presentation\OpportunityController;
use App\Src\ProfessionalProfile\Presentation\ProfessionalProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('professional-profile', [ProfessionalProfileController::class, 'index'])
        ->name('professional-profile.index');
    Route::post('professional-profile/experiences', [ProfessionalProfileController::class, 'storeExperience'])
        ->name('professional-profile.experiences.store');
    Route::delete('professional-profile/experiences/{experience}', [ProfessionalProfileController::class, 'destroyExperience'])
        ->name('professional-profile.experiences.destroy');
    Route::post('professional-profile/projects', [ProfessionalProfileController::class, 'storeProject'])
        ->name('professional-profile.projects.store');
    Route::delete('professional-profile/projects/{project}', [ProfessionalProfileController::class, 'destroyProject'])
        ->name('professional-profile.projects.destroy');

    Route::get('opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
    Route::post('opportunities', [OpportunityController::class, 'store'])->name('opportunities.store');
    Route::delete('opportunities/{opportunity}', [OpportunityController::class, 'destroy'])->name('opportunities.destroy');
    Route::post('opportunities/{opportunity}/questions', [OpportunityController::class, 'storeQuestion'])
        ->name('opportunities.questions.store');
    Route::delete('opportunity-questions/{question}', [OpportunityController::class, 'destroyQuestion'])
        ->name('opportunities.questions.destroy');
    Route::post('opportunity-questions/{question}/answer', [OpportunityController::class, 'generateAnswer'])
        ->name('opportunities.questions.answer');
});

require __DIR__.'/settings.php';
