<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category; // IMPORTAMOS EL MODELO
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        // Traemos los gastos junto con su categoría para no saturar la base de datos
        $expenses = Expense::with('category')->orderBy('expense_date', 'desc')->get();
        $total = Expense::sum('amount');

        $weekTotal = Expense::whereBetween('expense_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $monthTotal = Expense::whereMonth('expense_date', Carbon::now()->month)
                             ->whereYear('expense_date', Carbon::now()->year)
                             ->sum('amount');
        $yearTotal = Expense::whereYear('expense_date', Carbon::now()->year)->sum('amount');

        $unnecessaryTotal = Expense::where('is_essential', false)->sum('amount');

        // Traemos las categorías y sumamos automáticamente todos sus gastos
        $categories = Category::withSum('expenses', 'amount')->get();

        return view('welcome', compact('expenses', 'total', 'weekTotal', 'monthTotal', 'yearTotal', 'unnecessaryTotal', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id', // Validamos que elijan una categoría
        ]);

        Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'is_essential' => $request->has('is_essential'),
            'category_id' => $request->category_id, // Guardamos la categoría
        ]);

        return redirect('/');
    }

    // NUEVA FUNCIÓN PARA GUARDAR CATEGORÍAS
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function edit(Expense $expense)
    {
        // También mandamos las categorías a la vista de editar
        $categories = Category::all();
        return view('edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'is_essential' => $request->has('is_essential'),
            'category_id' => $request->category_id,
        ]);

        return redirect('/');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back();
    }
}