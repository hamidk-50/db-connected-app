@props([
    'customer' => null,
    'statuses' => [],
])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            :value="old('name', $customer?->name)"
            required
            autofocus
        />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full"
            :value="old('email', $customer?->email)"
            autocomplete="email"
        />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input
            id="phone"
            name="phone"
            type="text"
            class="mt-1 block w-full"
            :value="old('phone', $customer?->phone)"
        />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select
            id="status"
            name="status"
            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $customer?->status ?? 'prospect') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>
</div>
