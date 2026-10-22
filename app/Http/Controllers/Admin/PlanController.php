<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\TourPlan;
use App\Support\Slugs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index', [
            'plans' => TourPlan::withCount('stops')->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.plans.form', [
            'plan' => new TourPlan(['is_active' => true]),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $plan = TourPlan::create($this->payload($request));
        $this->syncStops($request, $plan);

        return redirect()->route('admin.plans.edit', $plan)->with('status', 'Tour plan created.');
    }

    public function edit(TourPlan $plan)
    {
        $plan->load('stops');

        return view('admin.plans.form', [
            'plan' => $plan,
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TourPlan $plan)
    {
        $plan->update($this->payload($request, $plan));
        $this->syncStops($request, $plan);

        return redirect()->route('admin.plans.edit', $plan)->with('status', 'Tour plan saved.');
    }

    public function destroy(TourPlan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('status', 'Tour plan removed.');
    }

    private function payload(Request $request, ?TourPlan $plan = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('tour_plans', 'slug')->ignore($plan)],
            'duration' => ['required', 'string', 'max:80'],
            'summary' => ['required', 'string', 'max:800'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'stops' => ['nullable', 'array'],
            'stops.*.destination_id' => ['nullable', 'integer', 'exists:destinations,id'],
            'stops.*.day_label' => ['nullable', 'string', 'max:40'],
        ]);

        return [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Slugs::unique(TourPlan::class, $data['title'], $plan?->id),
            'duration' => $data['duration'],
            'summary' => $data['summary'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }

    private function syncStops(Request $request, TourPlan $plan): void
    {
        $plan->stops()->delete();
        $order = 0;
        foreach ($request->input('stops', []) as $stop) {
            if (empty($stop['destination_id'])) {
                continue;
            }
            $plan->stops()->create([
                'destination_id' => $stop['destination_id'],
                'day_label' => $stop['day_label'] ?: null,
                'sort_order' => $order++,
            ]);
        }
    }
}
