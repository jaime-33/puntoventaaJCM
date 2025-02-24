<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\ProductoFormRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function __construct()
    {
        // Constructor code here (if any)
    }

    public function index(Request $request)
    {
        $texto = trim($request->get('texto'));
        $productos = DB::table('producto as a')
            ->join('categoria as c', 'a.id_categoria', '=', 'c.id_categoria')
            ->select('a.id_producto', 'a.nombre', 'a.codigo', 'a.stock', 'c.categoria', 'a.descripcion', 'a.imagen', 'a.estado')
            ->where('a.nombre', 'LIKE', '%' . $texto . '%')
            ->orWhere('a.codigo', 'LIKE', '%' . $texto . '%')
            ->orderBy('a.id_producto', 'desc')
            ->paginate(10);

        return view('almacen.producto.index', compact('productos', 'texto'));
    }

    public function create()
    {
        $categorias=DB::table('categoria')->where('status', '=', '1')->get();
        return view("almacen.producto.create",["categorias"=>$categorias]);
    }

    

    public function store(ProductoFormRequest $request)
    {

       



        $producto = new Producto;
        $producto->id_categoria = $request->input('id_categoria');
        $producto->codigo = $request->input('codigo');
        $producto->nombre = $request->input('nombre');
        $producto->stock = $request->input('stock');
        $producto->descripcion = $request->input('descripcion');
        $producto->estado = 'Activo';

        // Script para subir la imagen
        if ($request->hasFile("imagen")) {
            $imagen = $request->file("imagen");
            $nombreimagen = Str::slug($request->nombre).".".$imagen->guessExtension();
            $ruta = public_path("/imagenes/productos/");

            copy($imagen->getRealPath(),$ruta.$nombreimagen);
            $producto->imagen = $nombreimagen;
        }
        $producto->save();
        return redirect()->route('producto.index');
    }


    public function show($id)
    {

    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = DB::table('categoria')->where('status', '=', '1')->get();
        return view("almacen.producto.edit", ["producto" => $producto, "categorias"=>$categorias]);
        
        
        
    }

    public function update(ProductoFormRequest $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $producto->id_categoria = $request->input('id_categoria');
        $producto->codigo = $request->input('codigo');
        $producto->nombre = $request->input('nombre');
        $producto->stock = $request->input('stock');
        $producto->descripcion = $request->input('descripcion');

        // Script para subir la imagen
        if ($request->hasFile("imagen")) {
            $imagen = $request->file("imagen");
            $nombreimagen = Str::slug($request->nombre) . ".".$imagen->guessExtension();
            $ruta = public_path("/imagenes/productos/");
            copy($imagen->getRealPath(), $ruta.$nombreimagen);
            $producto->imagen = $nombreimagen;
        }

        $producto->update();

        return redirect()->route('producto.index');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 'Inactivo';
        $producto->update();
        return redirect()->route('producto.index')
        ->with('success', 'producto eliminado correctamente');

        
        
    }
}

