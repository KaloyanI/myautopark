<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expense Details for') }} {{ $car->brand }} {{ $car->model }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('cars.expenses.edit', [$car, $expense]) }}" class="sketchy-button bg-yellow-600 text-white hover:bg-yellow-500">
                    {{ __('Edit Expense') }}
                </a>
                <form action="{{ route('cars.expenses.destroy', [$car, $expense]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="sketchy-button bg-red-600 text-white hover:bg-red-500">
                        {{ __('Delete Expense') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sketchy-card bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="sketchy p-4">
                            <h3 class="text-lg font-medium mb-4">{{ __('Basic Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Title') }}</dt>
                                    <dd class="mt-1">{{ $expense->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                                    <dd class="mt-1">{{ $expense->getTypeLabel() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Amount') }}</dt>
                                    <dd class="mt-1">{{ $expense->formatted_amount }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                            @if($expense->payment_status == 'paid') bg-green-100 text-green-800 
                                            @elseif($expense->payment_status == 'overdue') bg-red-100 text-red-800 
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $expense->getStatusLabel() }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Dates -->
                        <div class="sketchy p-4">
                            <h3 class="text-lg font-medium mb-4">{{ __('Important Dates') }}</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Expense Date') }}</dt>
                                    <dd class="mt-1">{{ $expense->expense_date->format('M d, Y') }}</dd>
                                </div>
                                @if($expense->due_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</dt>
                                        <dd class="mt-1 @if($expense->isOverdue()) text-red-600 font-medium @endif">
                                            {{ $expense->due_date->format('M d, Y') }}
                                        </dd>
                                    </div>
                                @endif
                                @if($expense->expiry_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Expiry Date') }}</dt>
                                        <dd class="mt-1 @if($expense->isExpiringSoon()) text-yellow-600 font-medium @endif">
                                            {{ $expense->expiry_date->format('M d, Y') }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Additional Information -->
                        <div class="sketchy p-4">
                            <h3 class="text-lg font-medium mb-4">{{ __('Additional Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-3">
                                @if($expense->reference_number)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Reference Number') }}</dt>
                                        <dd class="mt-1">{{ $expense->reference_number }}</dd>
                                    </div>
                                @endif
                                @if($expense->provider)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Provider') }}</dt>
                                        <dd class="mt-1">{{ $expense->provider }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Documents -->
                        @if($expense->documents)
                            <div class="sketchy p-4">
                                <h3 class="text-lg font-medium mb-4">{{ __('Documents') }}</h3>
                                <ul class="space-y-2">
                                    @foreach($expense->documents as $index => $document)
                                        <li class="flex items-center space-x-2">
                                            <a href="{{ route('cars.expenses.document.download', [$car, $expense, $index]) }}" class="text-blue-600 hover:text-blue-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ basename($document) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Description and Notes -->
                    <div class="mt-6 space-y-6">
                        @if($expense->description)
                            <div class="sketchy p-4">
                                <h3 class="text-lg font-medium mb-4">{{ __('Description') }}</h3>
                                <p class="whitespace-pre-line">{{ $expense->description }}</p>
                            </div>
                        @endif

                        @if($expense->notes)
                            <div class="sketchy p-4">
                                <h3 class="text-lg font-medium mb-4">{{ __('Notes') }}</h3>
                                <p class="whitespace-pre-line">{{ $expense->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('cars.expenses.index', $car) }}" class="sketchy-button bg-gray-200 text-gray-800 hover:bg-gray-300">
                            {{ __('Back to Expenses') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 