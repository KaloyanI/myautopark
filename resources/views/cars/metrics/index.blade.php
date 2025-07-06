<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vehicle Metrics') }} - {{ $car->brand }} {{ $car->model }}
            </h2>
            <a href="{{ route('cars.metrics.create', $car) }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                {{ __('Add New Metric') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Latest Odometer Reading -->
                <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            {{ __('Latest Odometer Reading') }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($latestMetric?->odometer_reading ?? 0) }} km
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $latestMetric?->reading_date?->format('M d, Y') ?? __('No readings') }}
                        </div>
                    </div>
                </div>

                <!-- Average Fuel Efficiency -->
                <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            {{ __('Average Fuel Efficiency') }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($averageFuelEfficiency, 2) }} km/L
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Lifetime average') }}
                        </div>
                    </div>
                </div>

                <!-- Total Fuel Cost -->
                <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            {{ __('Total Fuel Cost') }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            ${{ number_format($totalFuelCost, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Lifetime spending') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Monthly Fuel Costs Chart -->
                <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Monthly Fuel Costs') }}
                        </h3>
                        <div class="h-64">
                            <canvas id="monthlyFuelCostsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Fuel Efficiency Trend Chart -->
                <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Fuel Efficiency Trend') }}
                        </h3>
                        <div class="h-64">
                            <canvas id="fuelEfficiencyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Table -->
            <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Metrics History') }}
                    </h3>
                    
                    @if($metrics->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="sketchy-table w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">{{ __('Date') }}</th>
                                        <th class="px-4 py-2">{{ __('Odometer') }}</th>
                                        <th class="px-4 py-2">{{ __('Fuel Amount') }}</th>
                                        <th class="px-4 py-2">{{ __('Fuel Cost') }}</th>
                                        <th class="px-4 py-2">{{ __('Efficiency') }}</th>
                                        <th class="px-4 py-2">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($metrics as $metric)
                                        <tr>
                                            <td class="px-4 py-2">{{ $metric->reading_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-2">{{ number_format($metric->odometer_reading) }} km</td>
                                            <td class="px-4 py-2">{{ number_format($metric->fuel_amount, 2) }} L</td>
                                            <td class="px-4 py-2">${{ number_format($metric->fuel_cost, 2) }}</td>
                                            <td class="px-4 py-2">
                                                @if($metric->fuel_efficiency)
                                                    {{ number_format($metric->fuel_efficiency, 2) }} km/L
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('cars.metrics.edit', [$car, $metric]) }}" 
                                                       class="sketchy-button bg-yellow-600 text-white hover:bg-yellow-500">
                                                        {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('cars.metrics.destroy', [$car, $metric]) }}" 
                                                          method="POST" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this metric?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="sketchy-button bg-red-600 text-white hover:bg-red-500">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $metrics->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No metrics recorded yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Fuel Costs Chart
        const monthlyFuelCostsCtx = document.getElementById('monthlyFuelCostsChart').getContext('2d');
        new Chart(monthlyFuelCostsCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyFuelCosts->pluck('month')),
                datasets: [{
                    label: '{{ __('Monthly Fuel Costs') }}',
                    data: @json($monthlyFuelCosts->pluck('total_cost')),
                    backgroundColor: '#4F46E5',
                    borderColor: '#4338CA',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Fuel Efficiency Trend Chart
        const fuelEfficiencyCtx = document.getElementById('fuelEfficiencyChart').getContext('2d');
        new Chart(fuelEfficiencyCtx, {
            type: 'line',
            data: {
                labels: @json($fuelEfficiencyTrend->pluck('reading_date')),
                datasets: [{
                    label: '{{ __('Fuel Efficiency (km/L)') }}',
                    data: @json($fuelEfficiencyTrend->pluck('fuel_efficiency')),
                    borderColor: '#059669',
                    backgroundColor: '#059669',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 