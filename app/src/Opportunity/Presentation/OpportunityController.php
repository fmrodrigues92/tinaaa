<?php

namespace App\Src\Opportunity\Presentation;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\OpportunityQuestion;
use App\Src\Opportunity\Application\OpportunityOverviewService;
use App\Src\Opportunity\Presentation\Requests\StoreOpportunityQuestionRequest;
use App\Src\Opportunity\Presentation\Requests\StoreOpportunityRequest;
use App\Src\RagAssistant\Application\GenerateOpportunityAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class OpportunityController extends Controller
{
    public function index(Request $request, OpportunityOverviewService $overview): Response
    {
        return Inertia::render('opportunities/index', $overview->forUser($request->user()));
    }

    public function store(StoreOpportunityRequest $request): RedirectResponse
    {
        Opportunity::query()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Opportunity added.')]);

        return to_route('opportunities.index');
    }

    public function destroy(Request $request, Opportunity $opportunity): RedirectResponse
    {
        abort_unless($opportunity->user_id === $request->user()->id, 404);

        $opportunity->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Opportunity removed.')]);

        return to_route('opportunities.index');
    }

    public function storeQuestion(
        StoreOpportunityQuestionRequest $request,
        Opportunity $opportunity,
    ): RedirectResponse {
        abort_unless($opportunity->user_id === $request->user()->id, 404);

        $opportunity->questions()->create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Question added.')]);

        return to_route('opportunities.index');
    }

    public function destroyQuestion(Request $request, OpportunityQuestion $question): RedirectResponse
    {
        $question->load('opportunity');

        abort_unless($question->opportunity->user_id === $request->user()->id, 404);

        $question->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Question removed.')]);

        return to_route('opportunities.index');
    }

    public function generateAnswer(
        Request $request,
        OpportunityQuestion $question,
        GenerateOpportunityAnswer $generateAnswer,
    ): RedirectResponse {
        $question->load('opportunity');

        abort_unless($question->opportunity->user_id === $request->user()->id, 404);

        try {
            $generateAnswer->handle($request->user(), $question);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Answer generated.')]);
        } catch (Throwable $exception) {
            report($exception);

            Log::warning('TINAAA answer generation failed.', [
                'question_id' => $question->id,
                'provider' => config('tinaaa.ai.provider'),
                'message' => $exception->getMessage(),
            ]);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => __('Could not generate the answer. Check the AI provider configuration.'),
            ]);
        }

        return to_route('opportunities.index');
    }
}
