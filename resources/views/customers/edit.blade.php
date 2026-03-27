<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Customer') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Back to customers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('customers.update', $customer) }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PATCH')

                    @include('customers.partials.form', ['customer' => $customer, 'statuses' => $statuses])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('Save Changes') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h3 class="text-base font-semibold text-gray-900">{{ __('Delete Customer') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('This action will permanently remove the customer from the system.') }}
                    </p>

                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            {{ __('Delete Customer') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
