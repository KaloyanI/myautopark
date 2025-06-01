<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cars') }}
            </h2>
            <a href="{{ route('cars.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Add New Car') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Filter Form -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-medium mb-3">{{ __('Filter Cars') }}</h3>
                        <form action="{{ route('cars.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="brand" class="block text-sm font-medium">{{ __('Brand') }}</label>
                                <select name="brand" id="brand" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">{{ __('All Brands') }}</option>
                                    @foreach(['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Tesla'] as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="model" class="block text-sm font-medium">{{ __('Model') }}</label>
                                <input type="text" name="model" id="model" value="{{ request('model') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="year" class="block text-sm font-medium">{{ __('Year') }}</label>
                                <input type="number" name="year" id="year" value="{{ request('year') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium">{{ __('Status') }}</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach(['available', 'maintenance', 'reserved', 'rented'] as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="price_min" class="block text-sm font-medium">{{ __('Min Price') }}</label>
                                <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="price_max" class="block text-sm font-medium">{{ __('Max Price') }}</label>
                                <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <div class="md:col-span-3 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Filter') }}
                                </button>
                                <a href="{{ route('cars.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Car Listing Table -->
                    <div class="overflow-x-auto relative">
                        @if($cars->count() > 0)
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">
                                            <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'brand', 'direction' => request('sort') == 'brand' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                {{ __('Brand') }}
                                                @if(request('sort') == 'brand')
                                                    <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'model', 'direction' => request('sort') == 'model' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                {{ __('Model') }}
                                                @if(request('sort') == 'model')
                                                    <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'year', 'direction' => request('sort') == 'year' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                {{ __('Year') }}
                                                @if(request('sort') == 'year')
                                                    <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'daily_rate', 'direction' => request('sort') == 'daily_rate' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                {{ __('Daily Rate') }}
                                                @if(request('sort') == 'daily_rate')
                                                    <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                {{ __('Status') }}
                                                @if(request('sort') == 'status')
                                                    <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cars as $car)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6">
                                                {{ $car->brand }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $car->model }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $car->year }}
                                            </td>
                                            <td class="py-4 px-6">
                                                ${{ number_format($car->daily_rate, 2) }}
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="px-2 py-1 rounded text-xs font-medium
                                                    @if($car->status == 'available') bg-green-100 text-green-800 
                                                    @elseif($car->status == 'maintenance') bg-orange-100 text-orange-800 
                                                    @elseif($car->status == 'reserved') bg-blue-100 text-blue-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($car->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 flex space-x-2">
                                                <a href="{{ route('cars.show', $car) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                    {{ __('View') }}
                                                </a>
                                                <a href="{{ route('cars.edit', $car) }}" class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this car?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $cars->appends(request()->query())->links() }}
                            </div>
                        @else
                            <p class="text-center py-4">{{ __('No cars found.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
