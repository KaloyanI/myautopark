<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cars') }}
            </h2>
            <a href="{{ route('cars.create') }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                {{ __('Add New Car') }}
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
                    
                    <!-- Car Listing Table -->
                    <div class="overflow-x-auto relative">
                        @if($cars->count() > 0)
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
                                        <th class="py-3 px-6">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cars as $car)
                                        <tr>
                                            <td class="py-4 px-6">
                                                @if($car->photo_url)
                                                    <img src="{{ $car->photo_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-16 h-16 object-cover rounded-lg sketchy">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-lg sketchy flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
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
                                                <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                                    @if($car->status == 'available') bg-green-100 text-green-800 
                                                    @elseif($car->status == 'maintenance') bg-orange-100 text-orange-800 
                                                    @elseif($car->status == 'reserved') bg-blue-100 text-blue-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($car->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 flex space-x-2">
                                                <a href="{{ route('cars.show', $car) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500">
                                                    {{ __('View') }}
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
