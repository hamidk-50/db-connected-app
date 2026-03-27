<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-semibold tracking-widest text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Add Customer') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 border-b border-gray-100">
                    <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="w-full sm:max-w-md">
                            <x-input-label for="q" :value="__('Search customers')" class="sr-only" />
                            <x-text-input id="q" name="q" type="text" class="block w-full" :value="$search" placeholder="Name, email, or phone" />
                        </div>
                        <div class="flex items-center gap-2">
                            <x-primary-button>{{ __('Search') }}</x-primary-button>
                            @if ($search !== '')
                                <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Clear') }}</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Phone') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $customer->email ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $customer->phone ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">{{ ucfirst($customer->status) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-800">{{ __('Edit') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">{{ __('No customers found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($customers as $customer)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $customer->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $customer->email ?? 'No email' }}</p>
                                </div>
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">{{ ucfirst($customer->status) }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $customer->phone ?? 'No phone' }}</p>
                            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                {{ __('Edit Customer') }}
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500">{{ __('No customers found.') }}</div>
                    @endforelse
                </div>

                @if ($customers->hasPages())
                    <div class="border-t border-gray-100 p-4 sm:p-6">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
