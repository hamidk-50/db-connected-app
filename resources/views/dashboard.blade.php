<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-500">Access Workspace</p>
                <h2 class="mt-1 text-2xl font-semibold text-slate-900">
                    {{ __('Multi-Portal Dashboard') }}
                </h2>
            </div>
            <p class="text-sm text-slate-600">
                {{ __('Signed in as') }} <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
            </p>
        </div>
    </x-slot>

    @php
        $moduleStyles = [
            'shipping' => [
                'tab' => 'from-cyan-500 to-sky-600',
                'pill' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
            ],
            'accounting' => [
                'tab' => 'from-emerald-500 to-teal-600',
                'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ],
            'inventory' => [
                'tab' => 'from-violet-500 to-indigo-600',
                'pill' => 'bg-violet-50 text-violet-700 border-violet-200',
            ],
        ];

        $activeModuleKey = $activeModule['key'] ?? null;
        $activeModuleStyle = $activeModuleKey ? ($moduleStyles[$activeModuleKey] ?? $moduleStyles['shipping']) : null;

        $portalData = $portalData ?? [];
        $isShippingCustomers = ($activeModule['key'] ?? null) === 'shipping' && ($activeItem['key'] ?? null) === 'customers';

        $customersTab = $portalData['customersTab'] ?? 'existing';
        $customerSearch = $portalData['customerSearch'] ?? '';
        $customers = $portalData['customers'] ?? null;
        $customerStatuses = $portalData['customerStatuses'] ?? ['prospect', 'active', 'inactive'];
        $returnToCurrent = $portalData['returnToCurrent'] ?? route('dashboard', ['module' => 'shipping', 'item' => 'customers', 'subtab' => 'existing']);
        $returnToCustomersExisting = $portalData['returnToCustomersExisting'] ?? route('dashboard', ['module' => 'shipping', 'item' => 'customers', 'subtab' => 'existing']);

        $isAdmin = auth()->user()?->hasRole('admin');
    @endphp

    <div class="py-8">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            @if (empty($modules) || count($modules) === 0)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-amber-900">{{ __('No Portals Assigned') }}</h3>
                    <p class="mt-1 text-sm text-amber-800">
                        {{ __('Your account currently has no portal permissions. Contact an administrator to assign access roles.') }}
                    </p>
                </div>
            @else
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach ($modules as $module)
                            @php
                                $isActiveModule = $activeModule && $activeModule['key'] === $module['key'];
                            @endphp
                            <a
                                href="{{ route('dashboard', ['module' => $module['key']]) }}"
                                class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-semibold transition
                                {{ $isActiveModule
                                    ? 'border-purple-600 bg-purple-600 text-white shadow-sm'
                                    : 'border-slate-300 bg-white text-slate-700 hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700' }}"
                            >
                                {{ $module['label'] }}
                            </a>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-slate-600">
                        {{ $activeModule['subtitle'] ?? '' }}
                    </p>
                </section>

                <section class="grid gap-6 lg:grid-cols-4">
                    <aside class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:col-span-1">
                        <nav class="space-y-2">
                            @forelse ($activeItems as $item)
                                @php
                                    $isActiveItem = $activeItem && $activeItem['key'] === $item['key'];
                                @endphp
                                <a
                                    href="{{ route('dashboard', ['module' => $activeModule['key'], 'item' => $item['key']]) }}"
                                    class="block rounded-xl border px-3 py-3 text-sm transition
                                    {{ $isActiveItem
                                        ? 'border-slate-800 bg-slate-900 text-white shadow-sm'
                                        : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}"
                                >
                                    <p class="font-semibold">{{ $item['label'] }}</p>
                                    <p class="mt-1 text-xs {{ $isActiveItem ? 'text-slate-200' : 'text-slate-500' }}">
                                        {{ $item['description'] }}
                                    </p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
                                    {{ __('No sections available for this portal.') }}
                                </div>
                            @endforelse
                        </nav>
                    </aside>

                    <div class="space-y-6 lg:col-span-3">
                        @if ($activeItem)
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div class="relative p-6 sm:p-8">
                                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $activeModuleStyle['tab'] ?? 'from-slate-500 to-slate-600' }}"></div>
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div class="max-w-2xl">
                                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $activeModuleStyle['pill'] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                {{ $activeModule['label'] }}
                                            </span>
                                            <h3 class="mt-3 text-2xl font-semibold text-slate-900">{{ $activeItem['label'] }}</h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                                {{ $activeItem['description'] }}
                                            </p>
                                        </div>

                                        @if (! $isShippingCustomers && !empty($activeItem['action']) && !empty($activeItem['action']['route']))
                                            <a
                                                href="{{ route($activeItem['action']['route']) }}"
                                                class="inline-flex items-center rounded-lg border border-slate-900 bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                                            >
                                                {{ $activeItem['action']['label'] ?? __('Open') }}
                                            </a>
                                        @endif
                                    </div>

                                    @if ($isShippingCustomers)
                                        <div class="mt-8 space-y-6">
                                            @if (session('status'))
                                                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                                                    {{ session('status') }}
                                                </div>
                                            @endif

                                            <div class="flex flex-wrap gap-2 rounded-xl border border-slate-200 bg-slate-50 p-2">
                                                <a
                                                    href="{{ route('dashboard', ['module' => 'shipping', 'item' => 'customers', 'subtab' => 'existing']) }}"
                                                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold transition {{ $customersTab === 'existing' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-white hover:text-slate-900' }}"
                                                >
                                                    Existing Customers
                                                </a>
                                                <a
                                                    href="{{ route('dashboard', ['module' => 'shipping', 'item' => 'customers', 'subtab' => 'new']) }}"
                                                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold transition {{ $customersTab === 'new' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-white hover:text-slate-900' }}"
                                                >
                                                    New Customer
                                                </a>
                                            </div>

                                            @if ($customersTab === 'existing')
                                                <div
                                                    x-data="{
                                                        q: @js($customerSearch),
                                                        timer: null,
                                                        requestId: 0,
                                                        loading: false,
                                                        endpoint: @js(route('dashboard.customers.search')),
                                                        refreshResults() {
                                                            const id = ++this.requestId;
                                                            const params = new URLSearchParams();
                                                            const term = this.q.trim();
                                                            if (term !== '') {
                                                                params.set('q', term);
                                                            }

                                                            this.loading = true;
                                                            fetch(this.endpoint + '?' + params.toString(), {
                                                                headers: {
                                                                    'Accept': 'application/json',
                                                                    'X-Requested-With': 'XMLHttpRequest',
                                                                },
                                                            })
                                                                .then((response) => response.json())
                                                                .then((payload) => {
                                                                    if (id !== this.requestId) return;
                                                                    this.$refs.customersResults.innerHTML = payload.html ?? '';
                                                                    if (window.Alpine) {
                                                                        window.Alpine.initTree(this.$refs.customersResults);
                                                                    }
                                                                })
                                                                .finally(() => {
                                                                    if (id === this.requestId) {
                                                                        this.loading = false;
                                                                    }
                                                                });
                                                        },
                                                        schedule() {
                                                            clearTimeout(this.timer);
                                                            this.timer = setTimeout(() => this.refreshResults(), 260);
                                                        },
                                                        clear() {
                                                            this.q = '';
                                                            this.schedule();
                                                            this.$nextTick(() => this.$refs.searchInput.focus());
                                                        }
                                                    }"
                                                    class="space-y-4"
                                                >
                                                    <form
                                                        method="GET"
                                                        action="{{ route('dashboard') }}"
                                                        @submit.prevent="refreshResults()"
                                                        class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between"
                                                    >
                                                        <div class="w-full sm:max-w-md">
                                                            <label for="customer-search" class="sr-only">Search customers</label>
                                                            <input
                                                                id="customer-search"
                                                                x-ref="searchInput"
                                                                type="text"
                                                                name="q"
                                                                x-model="q"
                                                                @input="schedule()"
                                                                placeholder="Search by name, email, or phone"
                                                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                                            >
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-500"
                                                            >
                                                                Search
                                                            </button>
                                                            <button
                                                                type="button"
                                                                x-show="q !== ''"
                                                                @click="clear()"
                                                                class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                                                            >
                                                                Clear
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <div x-show="loading" class="text-xs font-medium text-slate-500">
                                                        Searching...
                                                    </div>

                                                    <div x-ref="customersResults">
                                                        @include('portal.partials.customers-results', [
                                                            'customers' => $customers,
                                                            'customerStatuses' => $customerStatuses,
                                                            'isAdmin' => $isAdmin,
                                                            'returnToCurrent' => $returnToCurrent,
                                                        ])
                                                    </div>
                                                </div>
                                            @else
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                                                    <form method="POST" action="{{ route('customers.store') }}" class="grid gap-5 md:grid-cols-2">
                                                        @csrf
                                                        <input type="hidden" name="return_to" value="{{ $returnToCustomersExisting }}">

                                                        <div class="md:col-span-2">
                                                            <x-input-label for="new-customer-name" :value="__('Name')" />
                                                            <x-text-input
                                                                id="new-customer-name"
                                                                name="name"
                                                                type="text"
                                                                class="mt-1 block w-full"
                                                                :value="old('name')"
                                                                required
                                                            />
                                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                                        </div>

                                                        <div>
                                                            <x-input-label for="new-customer-email" :value="__('Email')" />
                                                            <x-text-input
                                                                id="new-customer-email"
                                                                name="email"
                                                                type="email"
                                                                class="mt-1 block w-full"
                                                                :value="old('email')"
                                                            />
                                                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                                        </div>

                                                        <div>
                                                            <x-input-label for="new-customer-phone" :value="__('Phone')" />
                                                            <x-text-input
                                                                id="new-customer-phone"
                                                                name="phone"
                                                                type="text"
                                                                class="mt-1 block w-full"
                                                                :value="old('phone')"
                                                            />
                                                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                                        </div>

                                                        <div>
                                                            <x-input-label for="new-customer-status" :value="__('Status')" />
                                                            <select
                                                                id="new-customer-status"
                                                                name="status"
                                                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                                                required
                                                            >
                                                                @foreach ($customerStatuses as $status)
                                                                    <option value="{{ $status }}" @selected(old('status', 'prospect') === $status)>
                                                                        {{ ucfirst($status) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                                        </div>

                                                        <div class="md:col-span-2 flex justify-end">
                                                            <button
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-500"
                                                            >
                                                                Create Customer
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endif
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
