@if ($customers && $customers->count() > 0)
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($customers as $customer)
            @php
                $statusStyles = [
                    'prospect' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'inactive' => 'bg-slate-100 text-slate-600 border-slate-200',
                ];
                $statusClass = $statusStyles[$customer->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                $modalName = 'edit-customer-'.$customer->id;
            @endphp

            <div class="flex h-full flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <h4 class="text-base font-semibold text-slate-900">{{ $customer->name }}</h4>
                    <span class="inline-flex rounded-full border px-2 py-0.5 text-xs font-semibold {{ $statusClass }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>

                <div class="mt-3 space-y-1 text-sm text-slate-600">
                    <p>{{ $customer->email ?: 'No email address' }}</p>
                    <p>{{ $customer->phone ?: 'No phone number' }}</p>
                </div>

                <div class="mt-5 flex items-center justify-end gap-2">
                    @if ($isAdmin)
                        <button
                            type="button"
                            x-data
                            x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-purple-400 hover:bg-purple-50 hover:text-purple-700"
                            title="Edit customer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                <path d="M13.586 3.586a2 2 0 0 1 2.828 2.828l-8.5 8.5a2 2 0 0 1-.878.513l-3 1a.75.75 0 0 1-.949-.948l1-3a2 2 0 0 1 .512-.879l8.5-8.5ZM12.525 5.707l1.768 1.768 1.06-1.06a.5.5 0 1 0-.707-.708l-1.06 1.06Z" />
                            </svg>
                        </button>

                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="return_to" value="{{ $returnToCurrent }}">
                            <button
                                type="submit"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-600 transition hover:bg-red-50"
                                title="Delete customer"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                    <path fill-rule="evenodd" d="M8.75 3a.75.75 0 0 0-.75.75V4h4v-.25a.75.75 0 0 0-.75-.75h-2.5ZM6.5 4V3.75A2.25 2.25 0 0 1 8.75 1.5h2.5A2.25 2.25 0 0 1 13.5 3.75V4h2.25a.75.75 0 0 1 0 1.5h-.638l-.698 10.476A2.25 2.25 0 0 1 12.17 18H7.83a2.25 2.25 0 0 1-2.244-2.024L4.888 5.5H4.25a.75.75 0 0 1 0-1.5H6.5Zm2.25 3a.75.75 0 0 1 .75.75v6a.75.75 0 0 1-1.5 0v-6A.75.75 0 0 1 8.75 7Zm3.25.75a.75.75 0 0 0-1.5 0v6a.75.75 0 0 0 1.5 0v-6Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <x-modal :name="$modalName" :show="old('edit_customer_id') == $customer->id && $errors->any()" max-width="lg" focusable>
                            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4 p-6">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="return_to" value="{{ $returnToCurrent }}">
                                <input type="hidden" name="edit_customer_id" value="{{ $customer->id }}">

                                <h5 class="text-lg font-semibold text-slate-900">Edit Customer</h5>

                                <div>
                                    <x-input-label :for="'edit-name-'.$customer->id" :value="__('Name')" />
                                    <x-text-input
                                        :id="'edit-name-'.$customer->id"
                                        name="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('edit_customer_id') == $customer->id ? old('name') : $customer->name"
                                        required
                                    />
                                    @if (old('edit_customer_id') == $customer->id)
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    @endif
                                </div>

                                <div>
                                    <x-input-label :for="'edit-email-'.$customer->id" :value="__('Email')" />
                                    <x-text-input
                                        :id="'edit-email-'.$customer->id"
                                        name="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        :value="old('edit_customer_id') == $customer->id ? old('email') : $customer->email"
                                    />
                                    @if (old('edit_customer_id') == $customer->id)
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    @endif
                                </div>

                                <div>
                                    <x-input-label :for="'edit-phone-'.$customer->id" :value="__('Phone')" />
                                    <x-text-input
                                        :id="'edit-phone-'.$customer->id"
                                        name="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('edit_customer_id') == $customer->id ? old('phone') : $customer->phone"
                                    />
                                    @if (old('edit_customer_id') == $customer->id)
                                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                    @endif
                                </div>

                                <div>
                                    <x-input-label :for="'edit-status-'.$customer->id" :value="__('Status')" />
                                    <select
                                        :id="'edit-status-'.$customer->id"
                                        name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        @foreach ($customerStatuses as $status)
                                            <option value="{{ $status }}" @selected((old('edit_customer_id') == $customer->id ? old('status') : $customer->status) === $status)>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (old('edit_customer_id') == $customer->id)
                                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                    @endif
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2">
                                    <button
                                        type="button"
                                        x-on:click="$dispatch('close-modal', '{{ $modalName }}')"
                                        class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-500"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-12 text-center text-sm text-slate-500">
        No customers match your search.
    </div>
@endif
