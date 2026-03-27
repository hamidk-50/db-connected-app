<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Display the access portal with role-aware tabs and side navigation.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $availableModules = $this->availableModules($user);

        if ($availableModules->isEmpty()) {
            return view('dashboard', [
                'modules' => [],
                'activeModule' => null,
                'activeItems' => collect(),
                'activeItem' => null,
            ]);
        }

        $activeModuleKey = (string) $request->query('module', $availableModules->first()['key']);
        $activeModule = $availableModules->firstWhere('key', $activeModuleKey) ?? $availableModules->first();

        $activeItems = collect($activeModule['items'])
            ->filter(fn (array $item): bool => $user->can($item['permission']))
            ->values();

        $activeItemKey = (string) $request->query('item', $activeItems->first()['key'] ?? '');
        $activeItem = $activeItems->firstWhere('key', $activeItemKey) ?? $activeItems->first();
        $portalData = $this->portalData($request, $activeModule, $activeItem);

        return view('dashboard', [
            'modules' => $availableModules,
            'activeModule' => $activeModule,
            'activeItems' => $activeItems,
            'activeItem' => $activeItem,
            'portalData' => $portalData,
        ]);
    }

    /**
     * Return the shipping customers result markup for live-search updates.
     */
    public function customersSearch(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user || ! $user->can('portal.shipping.access') || ! $user->can('nav.shipping.customers.view')) {
            abort(403);
        }

        $search = trim((string) $request->query('q', ''));
        $customers = $this->customersList($search);

        $html = view('portal.partials.customers-results', [
            'customers' => $customers,
            'customerStatuses' => ['prospect', 'active', 'inactive'],
            'isAdmin' => $user->hasRole('admin'),
            'returnToCurrent' => route('dashboard', [
                'module' => 'shipping',
                'item' => 'customers',
                'subtab' => 'existing',
            ]),
        ])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Resolve modules the user can access.
     */
    private function availableModules($user): Collection
    {
        return collect(config('portals.modules', []))
            ->map(function (array $module, string $key): array {
                return [...$module, 'key' => $key];
            })
            ->filter(fn (array $module): bool => $user->can($module['permission']))
            ->values();
    }

    /**
     * Resolve contextual data for specialized portal pages.
     *
     * @return array<string, mixed>
     */
    private function portalData(Request $request, ?array $activeModule, ?array $activeItem): array
    {
        if (! $activeModule || ! $activeItem) {
            return [];
        }

        if ($activeModule['key'] === 'shipping' && $activeItem['key'] === 'customers') {
            $customersTab = (string) $request->query('subtab', 'existing');
            if (! in_array($customersTab, ['existing', 'new'], true)) {
                $customersTab = 'existing';
            }

            $search = trim((string) $request->query('q', ''));
            $customers = $this->customersList($search);

            return [
                'customersTab' => $customersTab,
                'customerSearch' => $search,
                'customers' => $customers,
                'customerStatuses' => ['prospect', 'active', 'inactive'],
                'returnToCurrent' => $request->fullUrl(),
                'returnToCustomersExisting' => route('dashboard', [
                    'module' => 'shipping',
                    'item' => 'customers',
                    'subtab' => 'existing',
                ]),
            ];
        }

        return [];
    }

    /**
     * Build the customer list query for dashboard and live-search.
     */
    private function customersList(string $search): Collection
    {
        return Customer::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(200)
            ->get();
    }
}
