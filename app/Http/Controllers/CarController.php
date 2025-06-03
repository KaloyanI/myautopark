<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Booking;
use App\Models\Car;
use App\Helpers\FileHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return View|AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Car::query();
        
        // Apply filters
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }
        
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('min_price')) {
            $query->where('daily_rate', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', $request->max_price);
        }
        
        // Only show available cars if requested
        if ($request->boolean('available_only')) {
            $query->where('status', Car::STATUS_AVAILABLE);
        }
        
        // Sort results
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $allowedSortFields = ['brand', 'model', 'year', 'daily_rate', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        $cars = $query->paginate($request->get('per_page', 10));
        
        // Return the appropriate response based on what's requested
        if ($request->expectsJson()) {
            return CarResource::collection($cars);
        }
        
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return View
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CarRequest $request
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function store(CarRequest $request)
    {
        $data = $request->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = FileHelper::uploadFile($request->file('photo'));
        }
        
        $car = Car::create($data);
        
        if ($request->expectsJson()) {
            return (new CarResource($car))
                ->response()
                ->setStatusCode(201);
        }
        
        return redirect()
            ->route('cars.show', $car)
            ->with('success', 'Car created successfully!');
    }

    /**
     * Display the specified resource.
     * 
     * @param Request $request
     * @param Car $car
     * @return View|JsonResponse
     */
    public function show(Request $request, Car $car)
    {
        // Load related data
        $car->load([
            'bookings' => function ($query) {
                $query->orderBy('start_date', 'desc')->limit(5);
            },
            'maintenanceRecords' => function ($query) {
                $query->orderBy('service_date', 'desc')->limit(5);
            }
        ]);
        
        if ($request->expectsJson()) {
            return (new CarResource($car))->response();
        }
        
        return view('cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Car $car
     * @return View
     */
    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param CarRequest $request
     * @param Car $car
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function update(CarRequest $request, Car $car)
    {
        $data = $request->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = FileHelper::uploadFile($request->file('photo'), 'cars', $car->photo);
        }
        
        $car->update($data);
        
        if ($request->expectsJson()) {
            return (new CarResource($car))->response();
        }
        
        return redirect()
            ->route('cars.show', $car)
            ->with('success', 'Car updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Request $request
     * @param Car $car
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function destroy(Request $request, Car $car)
    {
        // Check if car has active bookings before deletion
        $hasActiveBookings = $car->bookings()
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->exists();
            
        if ($hasActiveBookings) {
            $message = 'Car cannot be deleted because it has active bookings.';
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            
            return back()->with('error', $message);
        }
        
        // Delete the photo if it exists
        if ($car->photo) {
            FileHelper::deleteFile($car->photo);
        }
        
        $car->delete();
        
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        
        return redirect()
            ->route('cars.index')
            ->with('success', 'Car deleted successfully!');
    }
    
    /**
     * Check if the car is available for a specific date range.
     * 
     * @param Request $request
     * @param Car $car
     * @return View|JsonResponse
     */
    public function checkAvailability(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Check if car status is available
        if ($car->status !== Car::STATUS_AVAILABLE) {
            $available = false;
            $message = 'This car is currently not available for booking.';
        } else {
            // Check for booking conflicts
            $conflictingBookings = $car->bookings()
                ->where(function (Builder $query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function (Builder $query) use ($startDate, $endDate) {
                            $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                ->exists();
                
            // Check for maintenance conflicts
            $maintenanceConflicts = $car->maintenanceRecords()
                ->where(function (Builder $query) use ($startDate, $endDate) {
                    $query->whereBetween('service_date', [$startDate, $endDate]);
                })
                ->whereIn('status', [
                    'scheduled', 'in_progress'
                ])
                ->exists();
                
            $available = !$conflictingBookings && !$maintenanceConflicts;
            $message = $available 
                ? 'Car is available for the selected dates.' 
                : 'Car is not available for the selected dates.';
        }
        
        $data = [
            'available' => $available,
            'message' => $message,
            'car' => $car,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('cars.availability', $data);
    }
    
    /**
     * Get all available cars for the API.
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getAvailableCars(Request $request)
    {
        $query = Car::where('status', Car::STATUS_AVAILABLE);
        
        // Apply any additional filters
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }
        
        if ($request->filled('min_price')) {
            $query->where('daily_rate', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', $request->max_price);
        }
        
        $cars = $query->paginate($request->get('per_page', 15));
        
        return CarResource::collection($cars);
    }
}
