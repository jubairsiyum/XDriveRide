<?php

namespace Modules\SubscriptionManagement\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('subscription_view');

        $plans = SubscriptionPlan::query()
            ->latest()
            ->paginate(function_exists('paginationLimit') ? paginationLimit() : 15)
            ->appends($request->query());

        return view('subscriptionmanagement::admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        $this->authorize('subscription_add');

        return view('subscriptionmanagement::admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('subscription_add');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        SubscriptionPlan::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'features' => $this->normalizeFeatures($request),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.subscriptions.plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function show(int $id): View
    {
        $this->authorize('subscription_view');

        $plan = SubscriptionPlan::query()
            ->withCount('subscriptions')
            ->findOrFail($id);

        return view('subscriptionmanagement::admin.plans.show', compact('plan'));
    }

    public function edit(int $id): View
    {
        $this->authorize('subscription_edit');

        $plan = SubscriptionPlan::findOrFail($id);

        return view('subscriptionmanagement::admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->authorize('subscription_edit');

        $plan = SubscriptionPlan::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $plan->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'features' => $this->normalizeFeatures($request),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.subscriptions.plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('subscription_delete');

        $plan = SubscriptionPlan::withCount('subscriptions')->findOrFail($id);

        if ($plan->subscriptions_count > 0) {
            return back()->with('error', 'This plan has subscriptions and cannot be deleted.');
        }

        $plan->delete();

        return back()->with('success', 'Subscription plan deleted successfully.');
    }

    private function normalizeFeatures(Request $request): array
    {
        $featuresInput = $request->input('features');

        if (is_array($featuresInput)) {
            $features = [];
            foreach ($featuresInput as $item) {
                if (!is_string($item)) {
                    continue;
                }
                foreach (preg_split('/\r\n|\r|\n|\|/', $item) as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $features[] = $line;
                    }
                }
            }
            return array_values(array_unique($features));
        }

        if (!is_string($featuresInput)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n|\|/', $featuresInput);

        return array_values(array_unique(array_filter(array_map('trim', $lines))));
    }
}
