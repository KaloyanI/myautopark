<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Car') }}: {{ $car->brand }} {{ $car->model }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('cars.show', $car) }}" class="sketchy-button bg-blue-600 text-white hover:bg-blue-500">
                    {{ __('View Details') }}
                </a>
                <a href="{{ route('cars.index') }}" class="sketchy-button bg-gray-800 text-white hover:bg-gray-700">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sketchy-card bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('cars.update', $car) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Car Information Section -->
                        <div class="mb-6 sketchy p-6">
                            <h3 class="text-lg font-medium pb-2 mb-4 border-b border-gray-200 dark:border-gray-700">{{ __('Car Information') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Photo -->
                                <div class="md:col-span-3 mb-4">
                                    <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Photo') }}</label>
                                    @if($car->photo_url)
                                        <div class="mb-4">
                                            <img src="{{ asset($car->photo_url) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-48 h-48 object-cover rounded sketchy">
                                        </div>
                                    @endif
                                    <input type="file" id="photo" name="photo" accept="image/*" class="sketchy-input mt-1 block w-full @error('photo') border-red-500 @enderror">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB') }}</p>
                                    @error('photo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Brand -->
                                <div>
                                    <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Brand') }} <span class="text-red-500">*</span></label>
                                    <select id="brand" name="brand" class="sketchy-input mt-1 block w-full @error('brand') border-red-500 @enderror">
                                        <option value="">{{ __('Select Brand') }}</option>
                                        @foreach(['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Tesla'] as $brand)
                                            <option value="{{ $brand }}" {{ old('brand', $car->brand) == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Model -->
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="model" name="model" value="{{ old('model', $car->model) }}" class="sketchy-input mt-1 block w-full @error('model') border-red-500 @enderror" required>
                                    @error('model')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Year -->
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Year') }} <span class="text-red-500">*</span></label>
                                    <input type="number" id="year" name="year" value="{{ old('year', $car->year) }}" min="1900" max="{{ date('Y') + 1 }}" class="sketchy-input mt-1 block w-full @error('year') border-red-500 @enderror" required>
                                    @error('year')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- License Plate -->
                                <div>
                                    <label for="license_plate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('License Plate') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="license_plate" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" class="sketchy-input mt-1 block w-full @error('license_plate') border-red-500 @enderror" required>
                                    @error('license_plate')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- VIN -->
                                <div>
                                    <label for="vin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('VIN') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="vin" name="vin" value="{{ old('vin', $car->vin) }}" class="sketchy-input mt-1 block w-full @error('vin') border-red-500 @enderror" required>
                                    @error('vin')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Color -->
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Color') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="color" name="color" value="{{ old('color', $car->color) }}" class="sketchy-input mt-1 block w-full @error('color') border-red-500 @enderror" required>
                                    @error('color')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Specifications Section -->
                        <div class="mb-6 sketchy p-6">
                            <h3 class="text-lg font-medium pb-2 mb-4 border-b border-gray-200 dark:border-gray-700">{{ __('Specifications') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Daily Rate -->
                                <div>
                                    <label for="daily_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Daily Rate') }} <span class="text-red-500">*</span></label>
                                    <div class="mt-1 relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $car->daily_rate) }}" min="0" step="0.01" class="sketchy-input pl-7 mt-1 block w-full @error('daily_rate') border-red-500 @enderror" required>
                                    </div>
                                    @error('daily_rate')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Mileage -->
                                <div>
                                    <label for="mileage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mileage') }} <span class="text-red-500">*</span></label>
                                    <input type="number" id="mileage" name="mileage" value="{{ old('mileage', $car->mileage) }}" min="0" class="sketchy-input mt-1 block w-full @error('mileage') border-red-500 @enderror" required>
                                    @error('mileage')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Fuel Type -->
                                <div>
                                    <label for="fuel_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Fuel Type') }} <span class="text-red-500">*</span></label>
                                    <select id="fuel_type" name="fuel_type" class="sketchy-input mt-1 block w-full @error('fuel_type') border-red-500 @enderror" required>
                                        <option value="">{{ __('Select Fuel Type') }}</option>
                                        @foreach(['gasoline', 'diesel', 'electric', 'hybrid'] as $type)
                                            <option value="{{ $type }}" {{ old('fuel_type', $car->fuel_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('fuel_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Transmission -->
                                <div>
                                    <label for="transmission" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Transmission') }} <span class="text-red-500">*</span></label>
                                    <select id="transmission" name="transmission" class="sketchy-input mt-1 block w-full @error('transmission') border-red-500 @enderror" required>
                                        <option value="">{{ __('Select Transmission') }}</option>
                                        @foreach(['automatic', 'manual', 'semi-automatic'] as $type)
                                            <option value="{{ $type }}" {{ old('transmission', $car->transmission) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('transmission')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Seats -->
                                <div>
                                    <label for="seats" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Seats') }} <span class="text-red-500">*</span></label>
                                    <input type="number" id="seats" name="seats" value="{{ old('seats', $car->seats) }}" min="1" max="20" class="sketchy-input mt-1 block w-full @error('seats') border-red-500 @enderror" required>
                                    @error('seats')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                    <select id="status" name="status" class="sketchy-input mt-1 block w-full @error('status') border-red-500 @enderror" required>
                                        <option value="">{{ __('Select Status') }}</option>
                                        @foreach(['available', 'maintenance', 'reserved', 'rented'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $car->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-6 sketchy p-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3" class="sketchy-input mt-1 block w-full @error('description') border-red-500 @enderror">{{ old('description', $car->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('cars.show', $car) }}" class="sketchy-button bg-gray-300 text-gray-800 hover:bg-gray-400">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="sketchy-button bg-blue-600 text-white hover:bg-blue-700">
                                {{ __('Update Car') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
