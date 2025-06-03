@php
    $isEdit = isset($expense);
    $route = $isEdit ? route('cars.expenses.update', [$car, $expense]) : route('cars.expenses.store', $car);
    $expense = $expense ?? null;
@endphp

<form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }} <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $expense?->title) }}" class="sketchy-input w-full @error('title') border-red-500 @enderror" required>
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expense Type -->
        <div>
            <label for="expense_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Expense Type') }} <span class="text-red-500">*</span></label>
            <select id="expense_type" name="expense_type" class="sketchy-input w-full @error('expense_type') border-red-500 @enderror" required>
                <option value="">{{ __('Select Type') }}</option>
                @foreach(['insurance', 'toll_tax', 'registration', 'fine', 'other'] as $type)
                    <option value="{{ $type }}" {{ old('expense_type', $expense?->expense_type) == $type ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
            @error('expense_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Amount -->
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }} <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                </div>
                <input type="number" id="amount" name="amount" value="{{ old('amount', $expense?->amount) }}" min="0" step="0.01" class="sketchy-input w-full pl-7 @error('amount') border-red-500 @enderror" required>
            </div>
            @error('amount')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expense Date -->
        <div>
            <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Expense Date') }} <span class="text-red-500">*</span></label>
            <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense?->expense_date?->format('Y-m-d')) }}" class="sketchy-input w-full @error('expense_date') border-red-500 @enderror" required>
            @error('expense_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Due Date -->
        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Due Date') }}</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $expense?->due_date?->format('Y-m-d')) }}" class="sketchy-input w-full @error('due_date') border-red-500 @enderror">
            @error('due_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expiry Date -->
        <div>
            <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Expiry Date') }}</label>
            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $expense?->expiry_date?->format('Y-m-d')) }}" class="sketchy-input w-full @error('expiry_date') border-red-500 @enderror">
            @error('expiry_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reference Number -->
        <div>
            <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Reference Number') }}</label>
            <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number', $expense?->reference_number) }}" class="sketchy-input w-full @error('reference_number') border-red-500 @enderror">
            @error('reference_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Provider -->
        <div>
            <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Provider') }}</label>
            <input type="text" id="provider" name="provider" value="{{ old('provider', $expense?->provider) }}" class="sketchy-input w-full @error('provider') border-red-500 @enderror">
            @error('provider')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Payment Status -->
        <div>
            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Status') }} <span class="text-red-500">*</span></label>
            <select id="payment_status" name="payment_status" class="sketchy-input w-full @error('payment_status') border-red-500 @enderror" required>
                @foreach(['pending', 'paid', 'overdue'] as $status)
                    <option value="{{ $status }}" {{ old('payment_status', $expense?->payment_status) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('payment_status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="3" class="sketchy-input w-full @error('description') border-red-500 @enderror">{{ old('description', $expense?->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Documents -->
    <div>
        <label for="documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Documents') }}</label>
        <input type="file" id="documents" name="documents[]" multiple class="sketchy-input w-full @error('documents') border-red-500 @enderror">
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('You can upload multiple documents (PDF, images, etc.)') }}</p>
        @error('documents')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        @if($isEdit && $expense?->documents)
            <div class="mt-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Current Documents') }}</h4>
                <ul class="space-y-2">
                    @foreach($expense->documents as $index => $document)
                        <li class="flex items-center space-x-2">
                            <a href="{{ route('cars.expenses.document.download', [$car, $expense, $index]) }}" class="text-blue-600 hover:text-blue-500">
                                {{ basename($document) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Notes -->
    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
        <textarea id="notes" name="notes" rows="3" class="sketchy-input w-full @error('notes') border-red-500 @enderror">{{ old('notes', $expense?->notes) }}</textarea>
        @error('notes')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end space-x-2">
        <a href="{{ route('cars.expenses.index', $car) }}" class="sketchy-button bg-gray-200 text-gray-800 hover:bg-gray-300">
            {{ __('Cancel') }}
        </a>
        <button type="submit" class="sketchy-button bg-indigo-600 text-white hover:bg-indigo-700">
            {{ $isEdit ? __('Update Expense') : __('Create Expense') }}
        </button>
    </div>
</form> 