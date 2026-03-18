<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Cálculos Filtrados por Usuario
        $total = $user->expenses()->sum('amount');
        
        $weekTotal = $user->expenses()
            ->whereBetween('expense_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');
            
        $monthTotal = $user->expenses()
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');
            
        $yearTotal = $user->expenses()
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');
            
        $unnecessaryTotal = $user->expenses()
            ->where('is_essential', false)
            ->sum('amount');

        // 2. Categorías del Usuario con sus totales
        $categories = $user->categories()->withSum('expenses', 'amount')->get();

        // 3. Lista de Gastos filtrada
        $query = $user->expenses()->with('category')->orderBy('expense_date', 'desc');
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        $expenses = $query->get();

        return view('welcome', compact('expenses', 'total', 'weekTotal', 'monthTotal', 'yearTotal', 'unnecessaryTotal', 'categories'));
    }

    public function store(Request $request)
    {
        // Validamos primero
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Guardamos el gasto
        auth()->user()->expenses()->create([
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'is_essential' => $request->has('is_essential'),
            'category_id' => $request->category_id,
        ]);

        return redirect('/dashboard');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        auth()->user()->categories()->create([
            'name' => $request->name
        ]);

        return back();
    }

    public function destroyCategory(Category $category)
    {
        // Solo puede borrar si es suya
        if ($category->user_id == auth()->id()) {
            $category->delete();
        }
        return redirect('/dashboard');
    }

    public function destroy(Expense $expense)
    {
        // Solo puede borrar si es suyo
        if ($expense->user_id == auth()->id()) {
            $expense->delete();
        }
        return back();
    }
}