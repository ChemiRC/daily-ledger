<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Gasto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-5 text-center text-gray-800">Editar Gasto</h1>
        
        <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <label class="block text-sm font-medium text-gray-700 mt-4">Fecha del Gasto</label>
<input type="date" name="expense_date" value="{{ $expense->expense_date }}" class="w-full p-2 border rounded" required>
            
            <label class="block text-sm font-medium text-gray-700">Monto ($)</label>
            <input type="number" name="amount" step="0.01" value="{{ $expense->amount }}" class="w-full p-2 border rounded" required>
            
            <label class="flex items-center space-x-2 cursor-pointer mt-4">
                <input type="checkbox" name="is_essential" value="1" {{ $expense->is_essential ? 'checked' : '' }} class="w-5 h-5 text-green-600">
                <span class="text-gray-700 font-medium">¿Es un gasto necesario?</span>
            </label>

            <div class="flex justify-between mt-6">
                <a href="/" class="text-gray-500 hover:underline mt-2">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded font-bold hover:bg-blue-700">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</body>
</html>