<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Daily Ledger - Mis Gastos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-5 text-center text-gray-800">Daily Ledger</h1>
        
        <div class="grid grid-cols-3 gap-4 mb-4 text-center">
            <div class="bg-blue-50 p-3 rounded shadow-sm">
                <h3 class="text-xs font-bold text-blue-600 uppercase">Semana</h3>
                <p class="text-lg font-bold text-gray-800">${{ number_format($weekTotal, 2) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded shadow-sm">
                <h3 class="text-xs font-bold text-blue-600 uppercase">Mes</h3>
                <p class="text-lg font-bold text-gray-800">${{ number_format($monthTotal, 2) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded shadow-sm">
                <h3 class="text-xs font-bold text-blue-600 uppercase">Año</h3>
                <p class="text-lg font-bold text-gray-800">${{ number_format($yearTotal, 2) }}</p>
            </div>
        </div>

        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 rounded shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-red-700 uppercase leading-tight">Fuga de Dinero<br>Total (Innecesarios)</h3>
                    <p class="text-2xl font-black text-red-800">${{ number_format($unnecessaryTotal, 2) }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="description" placeholder="¿En qué gastaste?" class="w-full p-2 border rounded" required>
            <input type="number" name="amount" step="0.01" placeholder="Monto ($)" class="w-full p-2 border rounded" required>
            <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border rounded" required>
            
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="is_essential" value="1" checked class="w-5 h-5 text-green-600">
                <span class="text-gray-700 font-medium">¿Es un gasto necesario?</span>
            </label>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700 transition">
                Registrar Gasto
            </button>
        </form>

        <hr class="my-8">

        <h2 class="font-bold text-lg mb-3">Historial de Gastos</h2>
        <ul class="space-y-2">
            @foreach($expenses as $expense)
                <li class="flex justify-between items-center p-3 rounded shadow-sm border {{ $expense->is_essential ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }}">
                    <div>
                        <strong class="text-gray-800">{{ $expense->description }}</strong> 
                        <small class="text-gray-500 block">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }} 
                            | {{ $expense->is_essential ? 'Necesario' : 'Innecesario' }}
                        </small>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="font-bold {{ $expense->is_essential ? 'text-gray-700' : 'text-red-600 text-lg' }}">
                            ${{ number_format($expense->amount, 2) }}
                        </span>
                        
                        <div class="flex flex-col space-y-1 text-right border-l pl-4 border-gray-200">
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-xs text-blue-500 hover:underline">Editar</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas borrar este gasto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 hover:underline">Borrar</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        @if($expenses->isEmpty())
            <p class="text-center text-gray-400 mt-4 italic">No hay gastos registrados aún.</p>
        @endif
    </div>
</body>
</html>