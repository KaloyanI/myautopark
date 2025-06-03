<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CarExpenseController extends Controller
{
    /**
     * Display a listing of the expenses for a specific car.
     */
    public function index(Car $car)
    {
        $expenses = $car->expenses()
            ->when(request('type'), function ($query, $type) {
                return $query->where('expense_type', $type);
            })
            ->when(request('status'), function ($query, $status) {
                return $query->where('payment_status', $status);
            })
            ->when(request('date_from'), function ($query, $date) {
                return $query->where('expense_date', '>=', Carbon::parse($date));
            })
            ->when(request('date_to'), function ($query, $date) {
                return $query->where('expense_date', '<=', Carbon::parse($date));
            })
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        $totalByType = [
            'insurance' => $car->getTotalExpensesByType(CarExpense::TYPE_INSURANCE),
            'toll_tax' => $car->getTotalExpensesByType(CarExpense::TYPE_TOLL_TAX),
            'registration' => $car->getTotalExpensesByType(CarExpense::TYPE_REGISTRATION),
            'fine' => $car->getTotalExpensesByType(CarExpense::TYPE_FINE),
            'other' => $car->getTotalExpensesByType(CarExpense::TYPE_OTHER),
        ];

        return view('cars.expenses.index', compact('car', 'expenses', 'totalByType'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(Car $car)
    {
        return view('cars.expenses.create', compact('car'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request, Car $car)
    {
        $validatedData = $request->validate(CarExpense::validationRules());
        
        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $path = $document->store('car-expenses', 'public');
                $documents[] = $path;
            }
            $validatedData['documents'] = $documents;
        }

        $car->expenses()->create($validatedData);

        return redirect()
            ->route('cars.expenses.index', $car)
            ->with('success', 'Expense record created successfully.');
    }

    /**
     * Display the specified expense.
     */
    public function show(Car $car, CarExpense $expense)
    {
        return view('cars.expenses.show', compact('car', 'expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Car $car, CarExpense $expense)
    {
        return view('cars.expenses.edit', compact('car', 'expense'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Car $car, CarExpense $expense)
    {
        $validatedData = $request->validate(CarExpense::validationRules());

        if ($request->hasFile('documents')) {
            // Delete old documents
            if ($expense->documents) {
                foreach ($expense->documents as $document) {
                    Storage::disk('public')->delete($document);
                }
            }

            // Store new documents
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $path = $document->store('car-expenses', 'public');
                $documents[] = $path;
            }
            $validatedData['documents'] = $documents;
        }

        $expense->update($validatedData);

        return redirect()
            ->route('cars.expenses.index', $car)
            ->with('success', 'Expense record updated successfully.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Car $car, CarExpense $expense)
    {
        // Delete associated documents
        if ($expense->documents) {
            foreach ($expense->documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $expense->delete();

        return redirect()
            ->route('cars.expenses.index', $car)
            ->with('success', 'Expense record deleted successfully.');
    }

    /**
     * Download a document associated with the expense.
     */
    public function downloadDocument(Car $car, CarExpense $expense, $index)
    {
        if (!$expense->documents || !isset($expense->documents[$index])) {
            abort(404);
        }

        $path = $expense->documents[$index];
        return Storage::disk('public')->download($path);
    }
} 