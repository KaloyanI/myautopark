<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Metric') }} - {{ $car->brand }} {{ $car->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sketchy-card bg-white dark:bg-gray-800 overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('cars.metrics.store', $car) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Reading Date -->
                        <div>
                            <x-input-label for="reading_date" :value="__('Reading Date')" />
                            <x-text-input id="reading_date" 
                                         type="date" 
                                         name="reading_date"
                                         :value="old('reading_date', now()->format('Y-m-d'))"
                                         class="mt-1 block w-full" 
                                         required />
                            <x-input-error :messages="$errors->get('reading_date')" class="mt-2" />
                        </div>

                        <!-- Odometer Reading -->
                        <div>
                            <x-input-label for="odometer_reading" :value="__('Odometer Reading (km)')" />
                            <x-text-input id="odometer_reading" 
                                         type="number" 
                                         name="odometer_reading"
                                         :value="old('odometer_reading')"
                                         class="mt-1 block w-full" 
                                         step="0.01"
                                         required />
                            <x-input-error :messages="$errors->get('odometer_reading')" class="mt-2" />
                        </div>

                        <!-- Fuel Amount -->
                        <div>
                            <x-input-label for="fuel_amount" :value="__('Fuel Amount (L)')" />
                            <x-text-input id="fuel_amount" 
                                         type="number" 
                                         name="fuel_amount"
                                         :value="old('fuel_amount')"
                                         class="mt-1 block w-full" 
                                         step="0.01"
                                         required />
                            <x-input-error :messages="$errors->get('fuel_amount')" class="mt-2" />
                        </div>

                        <!-- Fuel Cost -->
                        <div>
                            <x-input-label for="fuel_cost" :value="__('Fuel Cost ($)')" />
                            <x-text-input id="fuel_cost" 
                                         type="number" 
                                         name="fuel_cost"
                                         :value="old('fuel_cost')"
                                         class="mt-1 block w-full" 
                                         step="0.01"
                                         required />
                            <x-input-error :messages="$errors->get('fuel_cost')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes"
                                      name="notes"
                                      class="sketchy mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('cars.metrics.index', $car) }}" class="sketchy-button bg-gray-500 text-white hover:bg-gray-400">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 