<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cars') }}
            </h2>
            <div class="flex space-x-2">
                <!-- Layout Toggle -->
                <div class="flex space-x-1 mr-4">
                    <form action="{{ route('layout.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="view" value="cars.index">
                        <input type="hidden" name="layout" value="{{ \App\Helpers\LayoutHelper::getLayoutPreference('cars.index') === 'table' ? 'grid' : 'table' }}">
                        <button type="submit" class="sketchy-button bg-gray-600 text-white hover:bg-gray-500 flex items-center h-full">
                            @if(\App\Helpers\LayoutHelper::getLayoutPreference('cars.index') === 'table')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            @endif
                        </button>
                    </form>
                </div>
                <a href="{{ route('cars.create') }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                    {{ __('Add New Car') }}
                </a>
            </div>
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
                    
                    <!-- Filter Form -->
                    <div class="mb-6 p-4 sketchy bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-medium mb-3">{{ __('Filter Cars') }}</h3>
                        <form action="{{ route('cars.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="brand" class="block text-sm font-medium">{{ __('Brand') }}</label>
                                <select name="brand" id="brand" class="sketchy-input mt-1 block w-full">
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
                                <input type="text" name="model" id="model" value="{{ request('model') }}" class="sketchy-input mt-1 block w-full">
                            </div>
                            
                            <div>
                                <label for="year" class="block text-sm font-medium">{{ __('Year') }}</label>
                                <input type="number" name="year" id="year" value="{{ request('year') }}" class="sketchy-input mt-1 block w-full">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium">{{ __('Status') }}</label>
                                <select name="status" id="status" class="sketchy-input mt-1 block w-full">
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
                                <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" class="sketchy-input mt-1 block w-full">
                            </div>
                            
                            <div>
                                <label for="price_max" class="block text-sm font-medium">{{ __('Max Price') }}</label>
                                <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" class="sketchy-input mt-1 block w-full">
                            </div>
                            
                            <div class="md:col-span-3 flex justify-end space-x-2">
                                <button type="submit" class="sketchy-button bg-indigo-600 text-white hover:bg-indigo-700">
                                    {{ __('Filter') }}
                                </button>
                                <a href="{{ route('cars.index') }}" class="sketchy-button bg-gray-200 text-gray-800 hover:bg-gray-300">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Car Listing -->
                    <div class="relative">
                        @if($cars->count() > 0)
                            @if(\App\Helpers\LayoutHelper::getLayoutPreference('cars.index') === 'table')
                                <!-- Table Layout -->
                                <div class="overflow-x-auto">
                                    <table class="sketchy-table w-full">
                                        <thead>
                                            <tr>
                                                <th class="py-3 px-6">{{ __('Photo') }}</th>
                                                <th class="py-3 px-6">
                                                    <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'brand', 'direction' => request('sort') == 'brand' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                        {{ __('Brand') }}
                                                        @if(request('sort') == 'brand')
                                                            <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                            </svg>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th class="py-3 px-6">
                                                    <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'model', 'direction' => request('sort') == 'model' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                        {{ __('Model') }}
                                                        @if(request('sort') == 'model')
                                                            <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                            </svg>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th class="py-3 px-6">
                                                    <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'year', 'direction' => request('sort') == 'year' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                        {{ __('Year') }}
                                                        @if(request('sort') == 'year')
                                                            <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                            </svg>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th class="py-3 px-6">{{ __('Owner') }}</th>
                                                <th class="py-3 px-6">
                                                    <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'daily_rate', 'direction' => request('sort') == 'daily_rate' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                        {{ __('Daily Rate') }}
                                                        @if(request('sort') == 'daily_rate')
                                                            <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                            </svg>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th class="py-3 px-6">
                                                    <a href="{{ route('cars.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                                        {{ __('Status') }}
                                                        @if(request('sort') == 'status')
                                                            <svg class="w-3 h-3 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                            </svg>
                                                        @endif
                                                    </a>
                                                </th>
                                                <th class="py-3 px-6">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cars as $car)
                                                <tr>
                                                    <td class="py-4 px-6">
                                                        @if($car->photo_url)
                                                            <img src="{{ $car->photo_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-16 h-16 object-cover rounded sketchy border-0">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-200 rounded sketchy flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 px-6">{{ $car->brand }}</td>
                                                    <td class="py-4 px-6">{{ $car->model }}</td>
                                                    <td class="py-4 px-6">{{ $car->year }}</td>
                                                    <td class="py-4 px-6">{{ $car->owner->name }}</td>
                                                    <td class="py-4 px-6">${{ number_format($car->daily_rate, 2) }}</td>
                                                    <td class="py-4 px-6">
                                                        <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                                            @if($car->status == 'available') bg-green-100 text-green-800 
                                                            @elseif($car->status == 'maintenance') bg-orange-100 text-orange-800 
                                                            @elseif($car->status == 'reserved') bg-blue-100 text-blue-800 
                                                            @else bg-red-100 text-red-800 @endif">
                                                            {{ ucfirst($car->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 px-6 space-x-2">
                                                        <a href="{{ route('cars.show', $car) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500">
                                                            {{ __('View') }}
                                                        </a>
                                                        <a href="{{ route('cars.expenses.create', $car) }}" class="sketchy-button bg-green-600 text-white hover:bg-green-500">
                                                            {{ __('Add Expense') }}
                                                        </a>
                                                        <a href="{{ route('cars.edit', $car) }}" class="sketchy-button bg-yellow-600 text-white hover:bg-yellow-500">
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this car?');">
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
                            @else
                                <!-- Grid Layout -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($cars as $car)
                                        <div class="sketchy-card bg-white dark:bg-gray-700 overflow-visible">
                                            <div class="relative h-48 rounded overflow-hidden">
                                                @if($car->photo_url)
                                                    <img src="{{ $car->photo_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="absolute top-2 right-2 sketchy px-2 py-1 rounded text-xs font-medium
                                                    @if($car->status == 'available') bg-green-100 text-green-800 
                                                    @elseif($car->status == 'maintenance') bg-orange-100 text-orange-800 
                                                    @elseif($car->status == 'reserved') bg-blue-100 text-blue-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($car->status) }}
                                                </span>
                                            </div>
                                            <div class="p-4 overflow-visible">
                                                <h3 class="text-lg font-semibold mb-2">{{ $car->brand }} {{ $car->model }}</h3>
                                                <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                                    <p>{{ __('Year') }}: {{ $car->year }}</p>
                                                    <p>{{ __('Daily Rate') }}: ${{ number_format($car->daily_rate, 2) }}</p>
                                                </div>
                                                <div class="mt-4 flex items-center justify-between space-x-2">
                                                    <a href="{{ route('cars.show', $car) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500 text-sm flex-grow text-center">
                                                        {{ __('View') }}
                                                    </a>
                                                    <div class="relative" x-data="{ isOpen: false }">
                                                        <button @click="isOpen = !isOpen" type="button" class="sketchy-button bg-gray-600 text-white hover:bg-gray-500 text-sm px-3">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                            </svg>
                                                        </button>
                                                        <!-- Dropdown menu -->
                                                        <div x-show="isOpen" 
                                                             @click.away="isOpen = false"
                                                             x-cloak
                                                             class="absolute right-0 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 sketchy z-[9999] overflow-hidden"
                                                             style="display: none; margin-top: 0.5rem; transform: translateY(0);"
                                                             x-transition:enter="transition ease-out duration-100"
                                                             x-transition:enter-start="transform opacity-0 scale-95"
                                                             x-transition:enter-end="transform opacity-100 scale-100"
                                                             x-transition:leave="transition ease-in duration-75"
                                                             x-transition:leave-start="transform opacity-100 scale-100"
                                                             x-transition:leave-end="transform opacity-0 scale-95">
                                                            <div class="py-1 bg-white dark:bg-gray-700">
                                                                <a href="{{ route('cars.expenses.create', $car) }}" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                                    {{ __('Add Expense') }}
                                                                </a>
                                                                <a href="{{ route('cars.edit', $car) }}" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                                    {{ __('Edit') }}
                                                                </a>
                                                                <form action="{{ route('cars.destroy', $car) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this car?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                                        {{ __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $cars->appends(request()->query())->links() }}
                            </div>
                        @else
                            <p class="text-center py-4 sketchy">{{ __('No cars found.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
