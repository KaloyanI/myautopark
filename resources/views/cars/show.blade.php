<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $car->brand }} {{ $car->model }} ({{ $car->year }})
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('cars.edit', $car) }}" class="sketchy-button bg-yellow-600 text-white hover:bg-yellow-500">
                    {{ __('Edit Car') }}
                </a>
                <a href="{{ route('cars.index') }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 sketchy">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Car Details -->
            <div class="sketchy-card bg-white dark:bg-gray-800 mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">{{ __('Car Details') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Car Photo -->
                        @if($car->photo_url)
                            <div class="md:col-span-2 mb-6">
                                <img src="{{ asset($car->photo_url) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full max-w-2xl h-auto object-cover rounded sketchy mx-auto">
                            </div>
                        @endif
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Status') }}</h4>
                                <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                    @if($car->status == 'available') bg-green-100 text-green-800 
                                    @elseif($car->status == 'maintenance') bg-orange-100 text-orange-800 
                                    @elseif($car->status == 'reserved') bg-blue-100 text-blue-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($car->status) }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('License Plate') }}</h4>
                                <p>{{ $car->license_plate }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Owner') }}</h4>
                                <p>{{ $car->owner->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('VIN') }}</h4>
                                <p>{{ $car->vin }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Color') }}</h4>
                                <p>{{ $car->color }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Daily Rate') }}</h4>
                                <p>${{ number_format($car->daily_rate, 2) }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Mileage') }}</h4>
                                <p>{{ number_format($car->mileage) }} km</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Fuel Type') }}</h4>
                                <p>{{ ucfirst($car->fuel_type) }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Transmission') }}</h4>
                                <p>{{ ucfirst($car->transmission) }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Seats') }}</h4>
                                <p>{{ $car->seats }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Last Maintenance') }}</h4>
                                <p>{{ $car->last_maintenance ? $car->last_maintenance->format('M d, Y') : 'N/A' }}</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Expenses') }}</h4>
                                <a href="{{ route('cars.expenses.index', $car) }}" class="text-blue-600 hover:text-blue-500">
                                    {{ __('View All Expenses') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Description') }}</h4>
                        <p class="whitespace-pre-line">{{ $car->description }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <div class="flex">
                            <a href="{{ route('cars.availability', $car) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500">
                                {{ __('Check Availability') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking History -->
            <div class="sketchy-card bg-white dark:bg-gray-800 mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Booking History') }}</h3>
                    
                    @if($car->bookings && $car->bookings->count() > 0)
                        <div class="overflow-x-auto relative">
                            <table class="sketchy-table">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6">{{ __('Customer') }}</th>
                                        <th class="py-3 px-6">{{ __('Start Date') }}</th>
                                        <th class="py-3 px-6">{{ __('End Date') }}</th>
                                        <th class="py-3 px-6">{{ __('Total Days') }}</th>
                                        <th class="py-3 px-6">{{ __('Total Cost') }}</th>
                                        <th class="py-3 px-6">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($car->bookings as $booking)
                                        <tr>
                                            <td class="py-4 px-6">{{ $booking->customer_name }}</td>
                                            <td class="py-4 px-6">{{ $booking->start_date->format('M d, Y') }}</td>
                                            <td class="py-4 px-6">{{ $booking->end_date->format('M d, Y') }}</td>
                                            <td class="py-4 px-6">{{ $booking->start_date->diffInDays($booking->end_date) }}</td>
                                            <td class="py-4 px-6">${{ number_format($booking->total_cost, 2) }}</td>
                                            <td class="py-4 px-6">
                                                <span class="sketchy px-2 py-1 rounded text-xs font-medium
                                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800 
                                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800 
                                                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-4">{{ __('No booking history available.') }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Maintenance Records -->
            <div class="sketchy-card bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Maintenance Records') }}</h3>
                    
                    @if($car->maintenances && $car->maintenances->count() > 0)
                        <div class="overflow-x-auto relative">
                            <table class="sketchy-table">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6">{{ __('Date') }}</th>
                                        <th class="py-3 px-6">{{ __('Type') }}</th>
                                        <th class="py-3 px-6">{{ __('Description') }}</th>
                                        <th class="py-3 px-6">{{ __('Cost') }}</th>
                                        <th class="py-3 px-6">{{ __('Odometer') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($car->maintenances as $maintenance)
                                        <tr>
                                            <td class="py-4 px-6">{{ $maintenance->date->format('M d, Y') }}</td>
                                            <td class="py-4 px-6">{{ ucfirst($maintenance->type) }}</td>
                                            <td class="py-4 px-6">{{ $maintenance->description }}</td>
                                            <td class="py-4 px-6">${{ number_format($maintenance->cost, 2) }}</td>
                                            <td class="py-4 px-6">{{ number_format($maintenance->odometer) }} km</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-4">{{ __('No maintenance records available.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
