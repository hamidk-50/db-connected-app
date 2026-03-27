<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Customer') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Back to customers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('customers.store') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    @include('customers.partials.form', ['statuses' => $statuses])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('Create Customer') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
