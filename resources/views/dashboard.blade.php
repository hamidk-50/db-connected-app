<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-base font-semibold">{{ __('Welcome back') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('You are logged in and ready to manage your data.') }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-base font-semibold">{{ __('Customers Module') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Create, update, and search your customer records from one place.') }}</p>
                        <a href="{{ route('customers.index') }}" class="mt-4 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            {{ __('Open customers') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
