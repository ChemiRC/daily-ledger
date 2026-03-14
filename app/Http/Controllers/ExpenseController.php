<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        // Ordenamos por fecha del gasto
        $expenses = Expense::orderBy('expense_date', 'desc')->get();
        $total = Expense::sum('amount');

        // Cálculos basados en la columna 'expense_date'
        $weekTotal = Expense::whereBetween('expense_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $monthTotal = Expense::whereMonth('expense_date', Carbon::now()->month)
                             ->whereYear('expense_date', Carbon::now()->year)
                             ->sum('amount');
        $yearTotal = Expense::whereYear('expense_date', Carbon::now()->year)->sum('amount');

        // Sumar solo gastos innecesarios
        $unnecessaryTotal = Expense::where('is_essential', false)->sum('amount');

        return view('welcome', compact('expenses', 'total', 'weekTotal', 'monthTotal', 'yearTotal', 'unnecessaryTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'is_essential' => $request->has('is_essential'),
        ]);

        return redirect('/');
    }

    public function edit(Expense $expense)
    {
        return view('edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
        ]);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'is_essential' => $request->has('is_essential'),
        ]);

        return redirect('/');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back();
    }
}