<?php

namespace App\Src\ProfessionalProfile\Presentation;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalExperience;
use App\Models\ProfessionalProject;
use App\Src\ProfessionalProfile\Application\ProfileOverviewService;
use App\Src\ProfessionalProfile\Presentation\Requests\StoreProfessionalExperienceRequest;
use App\Src\ProfessionalProfile\Presentation\Requests\StoreProfessionalProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfessionalProfileController extends Controller
{
    public function index(Request $request, ProfileOverviewService $overview): Response
    {
        return Inertia::render('professional-profile/index', $overview->forUser($request->user()));
    }

    public function storeExperience(StoreProfessionalExperienceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_current'] = $request->boolean('is_current');
        $data['technologies'] = $this->technologiesFrom($data['technologies'] ?? null);

        ProfessionalExperience::query()->create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experience added.')]);

        return to_route('professional-profile.index');
    }

    public function destroyExperience(Request $request, ProfessionalExperience $experience): RedirectResponse
    {
        abort_unless($experience->user_id === $request->user()->id, 404);

        $experience->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experience removed.')]);

        return to_route('professional-profile.index');
    }

    public function storeProject(StoreProfessionalProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['professional_experience_id'])) {
            ProfessionalExperience::query()
                ->whereBelongsTo($request->user())
                ->whereKey($data['professional_experience_id'])
                ->firstOrFail();
        }

        $data['user_id'] = $request->user()->id;
        $data['technologies'] = $this->technologiesFrom($data['technologies'] ?? null);

        ProfessionalProject::query()->create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Project added.')]);

        return to_route('professional-profile.index');
    }

    public function destroyProject(Request $request, ProfessionalProject $project): RedirectResponse
    {
        abort_unless($project->user_id === $request->user()->id, 404);

        $project->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Project removed.')]);

        return to_route('professional-profile.index');
    }

    /**
     * @return list<string>
     */
    private function technologiesFrom(?string $value): array
    {
        if ($value === null) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $technology): string => trim($technology))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
