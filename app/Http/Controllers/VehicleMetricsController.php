<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\VehicleMetric;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VehicleMetricsController extends Controller
{
    public function index(Car $car): View
    {
        $metrics = $car->metrics()
            ->latest('reading_date')
            ->paginate(10);

        $latestMetric = $car->getLatestMetric();
        $averageFuelEfficiency = $car->getAverageFuelEfficiency();
        $totalFuelCost = $car->getTotalFuelCost();

        // Get monthly fuel costs for chart
        $monthlyFuelCosts = $car->metrics()
            ->selectRaw('DATE_FORMAT(reading_date, "%Y-%m") as month, SUM(fuel_cost) as total_cost')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get fuel efficiency trend for chart
        $fuelEfficiencyTrend = $car->metrics()
            ->whereNotNull('fuel_efficiency')
            ->select('reading_date', 'fuel_efficiency')
            ->orderBy('reading_date')
            ->get();

        return view('cars.metrics.index', compact(
            'car',
            'metrics',
            'latestMetric',
            'averageFuelEfficiency',
            'totalFuelCost',
            'monthlyFuelCosts',
            'fuelEfficiencyTrend'
        ));
    }

    public function create(Car $car): View
    {
        return view('cars.metrics.create', compact('car'));
    }

    public function store(Request $request, Car $car): RedirectResponse
    {
        $validated = $request->validate([
            'odometer_reading' => 'required|numeric|min:0',
            'fuel_amount' => 'required|numeric|min:0',
            'fuel_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'reading_date' => 'required|date',
        ]);

        $metric = $car->metrics()->create($validated);

        // Calculate fuel efficiency based on previous reading
        $previousMetric = $car->metrics()
            ->where('reading_date', '<', $validated['reading_date'])
            ->orderBy('reading_date', 'desc')
            ->first();

        $metric->calculateFuelEfficiency($previousMetric?->odometer_reading);

        return redirect()
            ->route('cars.metrics.index', $car)
            ->with('success', 'Vehicle metric added successfully.');
    }

    public function edit(Car $car, VehicleMetric $metric): View
    {
        return view('cars.metrics.edit', compact('car', 'metric'));
    }

    public function update(Request $request, Car $car, VehicleMetric $metric): RedirectResponse
    {
        $validated = $request->validate([
            'odometer_reading' => 'required|numeric|min:0',
            'fuel_amount' => 'required|numeric|min:0',
            'fuel_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'reading_date' => 'required|date',
        ]);

        $metric->update($validated);

        // Recalculate fuel efficiency
        $previousMetric = $car->metrics()
            ->where('reading_date', '<', $validated['reading_date'])
            ->where('id', '!=', $metric->id)
            ->orderBy('reading_date', 'desc')
            ->first();

        $metric->calculateFuelEfficiency($previousMetric?->odometer_reading);

        // Update next metric's fuel efficiency if exists
        $nextMetric = $car->metrics()
            ->where('reading_date', '>', $validated['reading_date'])
            ->orderBy('reading_date')
            ->first();

        if ($nextMetric) {
            $nextMetric->calculateFuelEfficiency($metric->odometer_reading);
        }

        return redirect()
            ->route('cars.metrics.index', $car)
            ->with('success', 'Vehicle metric updated successfully.');
    }

    public function destroy(Car $car, VehicleMetric $metric): RedirectResponse
    {
        $metric->delete();

        return redirect()
            ->route('cars.metrics.index', $car)
            ->with('success', 'Vehicle metric deleted successfully.');
    }
}
