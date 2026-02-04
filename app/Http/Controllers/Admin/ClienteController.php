<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    private function validarAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Acceso solo para administradores.');
        }
    }


    public function index(Request $request)
    {
        $this->validarAdmin();

        $q = Cliente::query();

        if ($buscar = $request->get('buscar')) {
            // Escapar % y _ para evitar resultados raros en LIKE
            $buscar = addcslashes($buscar, '%_');

            $q->where(function ($qq) use ($buscar) {
                $qq->where('nombre_contacto', 'like', "%{$buscar}%")
                    ->orWhere('empresa', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%")
                    ->orWhere('telefono_alterno', 'like', "%{$buscar}%")
                    ->orWhere('ciudad', 'like', "%{$buscar}%")
                    ->orWhere('documento', 'like', "%{$buscar}%");
            });
        }

        $clientes = $q->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->validarAdmin();
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'tipo_cliente' => 'required|in:persona,empresa',
            'nombre_contacto' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'telefono_alterno' => 'nullable|string|max:50',
            'pais' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
        ]);

        Cliente::create($data);

        return redirect()
            ->route('admin.clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        $this->validarAdmin();
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'tipo_cliente' => 'required|in:persona,empresa',
            'nombre_contacto' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'telefono_alterno' => 'nullable|string|max:50',
            'pais' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
        ]);

        $cliente->update($data);

        return back()->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->validarAdmin();

        $cliente->delete();

        return redirect()
            ->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
