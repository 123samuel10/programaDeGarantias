<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Producto;

use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
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

        $q = Producto::query();

        if ($buscar = $request->get('buscar')) {
            $buscar = addcslashes($buscar, '%_');
            $q->where(function ($qq) use ($buscar) {
                $qq->where('marca', 'like', "%{$buscar}%")
                   ->orWhere('modelo', 'like', "%{$buscar}%")
                   ->orWhere('descripcion', 'like', "%{$buscar}%")
                   ->orWhere('tipo_equipo', 'like', "%{$buscar}%");
            });
        }

        $productos = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $this->validarAdmin();
        return view('admin.productos.create');
    }

    public function store(Request $request)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'tipo_equipo' => 'nullable|string|max:255',
        ]);

        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function edit(Producto $producto)
    {
        $this->validarAdmin();
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'tipo_equipo' => 'nullable|string|max:255',
        ]);

        $producto->update($data);

        return back()->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $this->validarAdmin();
        $producto->delete();

        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
    }
}
