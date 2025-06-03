<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expenses for') }} {{ $car->brand }} {{ $car->model }}
            </h2>
            <a href="{{ route('cars.expenses.create', $car) }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                {{ __('Add New Expense') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sketchy-card bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 sketchy">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                        @foreach($totalByType as $type => $total)
                            <div class="sketchy p-4 bg-gray-50 dark:bg-gray-700">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $type)) }}</h3>
                                <p class="text-2xl font-bold">${{ number_format($total, 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Filter Form -->
                    <div class="mb-6 p-4 sketchy bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-medium mb-3">{{ __('Filter Expenses') }}</h3>
                        <form action="{{ route('cars.expenses.index', $car) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium">{{ __('Type') }}</label>
                                <select name="type" id="type" class="sketchy-input mt-1 block w-full">
                                    <option value="">{{ __('All Types') }}</option>
                                    @foreach(['insurance', 'toll_tax', 'registration', 'fine', 'other'] as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium">{{ __('Status') }}</label>
                                <select name="status" id="status" class="sketchy-input mt-1 block w-full">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach(['pending', 'paid', 'overdue'] as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="block text-sm font-medium">{{ __('Date From') }}</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="sketchy-input mt-1 block w-full">
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium">{{ __('Date To') }}</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="sketchy-input mt-1 block w-full">
                            </div>

                            <div class="md:col-span-4 flex justify-end space-x-2">
                                <button type="submit" class="sketchy-button bg-indigo-600 text-white hover:bg-indigo-700">
                                    {{ __('Filter') }}
                                </button>
                                <a href="{{ route('cars.expenses.index', $car) }}" class="sketchy-button bg-gray-200 text-gray-800 hover:bg-gray-300">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Expenses Table -->
                    @if($expenses->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="sketchy-table w-full">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6">{{ __('Title') }}</th>
                                        <th class="py-3 px-6">{{ __('Type') }}</th>
                                        <th class="py-3 px-6">{{ __('Amount') }}</th>
                                        <th class="py-3 px-6">{{ __('Date') }}</th>
                                        <th class="py-3 px-6">{{ __('Due Date') }}</th>
                                        <th class="py-3 px-6">{{ __('Status') }}</th>
                                        <th class="py-3 px-6">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td class="py-4 px-6">{{ $expense->title }}</td>
                                            <td class="py-4 px-6">{{ $expense->getTypeLabel() }}</td>
                                            <td class="py-4 px-6">{{ $expense->formatted_amount }}</td>
                                            <td class="py-4 px-6">{{ $expense->expense_date->format('M d, Y') }}</td>
                                            <td class="py-4 px-6">
                                                @if($expense->due_date)
                                                    <span class="@if($expense->isOverdue()) text-red-600 font-medium @endif">
                                                        {{ $expense->due_date->format('M d, Y') }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                                    @if($expense->payment_status == 'paid') bg-green-100 text-green-800 
                                                    @elseif($expense->payment_status == 'overdue') bg-red-100 text-red-800 
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $expense->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 space-x-2">
                                                <a href="{{ route('cars.expenses.show', [$car, $expense]) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500">
                                                    {{ __('View') }}
                                                </a>
                                                <a href="{{ route('cars.expenses.edit', [$car, $expense]) }}" class="sketchy-button bg-yellow-600 text-white hover:bg-yellow-500">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('cars.expenses.destroy', [$car, $expense]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="sketchy-button bg-red-600 text-white hover:bg-red-500">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $expenses->appends(request()->query())->links() }}
                        </div>
                    @else
                        <p class="text-center py-4 sketchy">{{ __('No expenses found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 