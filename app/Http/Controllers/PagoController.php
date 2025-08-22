<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Trabajos;
use App\Models\EstadoPago;
use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagos = Pagos::with(['trabajo.cliente','trabajo.servicio','estadoPago'])->get();
        $clientes = Clientes::orderBy('nombre')->get(['id','nombre','apellido']);

        $clienteId = $request->query('cliente');
        $resumen = null;
        $trabajosPendientes = [];
        
        if ($clienteId) {
            $trabajosCliente = Trabajos::with('servicio')->where('idCliente', $clienteId)->get();
            $totalTrabajos = $trabajosCliente->sum(fn($t) => (float) ($t->servicio->precioReferencial ?? 0));
            $pagosCliente = Pagos::with('estadoPago','trabajo')->whereIn('idTrabajo', $trabajosCliente->pluck('id'))->get();
            $totalPagado = $pagosCliente->filter(fn($p) => strtolower($p->estadoPago->nombre ?? '') === 'pagado')->sum('aCuenta');
            $totalPendiente = max($totalTrabajos - $totalPagado, 0);

            // Trabajos sin un pago con estado 'Pagado'
            $trabajosPagadosIds = $pagosCliente->filter(fn($p) => strtolower($p->estadoPago->nombre ?? '') === 'pagado')->pluck('idTrabajo')->unique()->values();
            $trabajosPendientes = $trabajosCliente->whereNotIn('id', $trabajosPagadosIds)->pluck('id')->values()->all();

            $resumen = [
                'clienteId' => (int) $clienteId,
                'totalTrabajos' => round($totalTrabajos, 2),
                'totalPagado' => round($totalPagado, 2),
                'totalPendiente' => round($totalPendiente, 2)
            ];
        }

        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
            'clientes' => $clientes,
            'resumen' => $resumen,
            'trabajosPendientes' => $trabajosPendientes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trabajos = Trabajos::with(['cliente', 'servicio', 'detalleTrabajo'])->get();
        $estadosPago = EstadoPago::all();
        
        return Inertia::render('Pagos/Create', [
            'trabajos' => $trabajos,
            'estadosPago' => $estadosPago
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'aCuenta' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Calcular automáticamente usando el modelo
            $datosPago = Pagos::calcularPago($request->idTrabajo, $request->aCuenta);
            
            // Crear el pago
            Pagos::create([
                'idTrabajo' => $request->idTrabajo,
                'total' => $datosPago['total'],
                'aCuenta' => $datosPago['aCuenta'],
                'saldo' => $datosPago['saldo'],
                'idEstadoPago' => $datosPago['idEstadoPago'],
            ]);

            return redirect()->route('pagos')->with('success', 'Pago registrado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pago = Pagos::with(['trabajo.cliente', 'trabajo.servicio', 'estadoPago'])->findOrFail($id);
        
        return Inertia::render('Pagos/Show', [
            'pago' => $pago
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pago = Pagos::findOrFail($id);
        $trabajos = Trabajos::with(['cliente', 'servicio', 'detalleTrabajo'])->get();
        $estadosPago = EstadoPago::all();
        
        return Inertia::render('Pagos/Edit', [
            'pago' => $pago,
            'trabajos' => $trabajos,
            'estadosPago' => $estadosPago
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pago = Pagos::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'idTrabajo' => 'required|exists:trabajos,id',
            'aCuenta' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Recalcular el pago
            $datosPago = Pagos::calcularPago($request->idTrabajo, $request->aCuenta);
            
            $pago->update([
                'idTrabajo' => $request->idTrabajo,
                'total' => $datosPago['total'],
                'aCuenta' => $datosPago['aCuenta'],
                'saldo' => $datosPago['saldo'],
                'idEstadoPago' => $datosPago['idEstadoPago'],
            ]);

            return redirect()->route('pagos')->with('success', 'Pago actualizado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pago = Pagos::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos')->with('success', 'Pago eliminado correctamente');
    }

    /**
     * Obtener pagos por trabajo
     */
    public function porTrabajo($trabajoId)
    {
        $pagos = Pagos::with(['trabajo', 'estadoPago'])
            ->where('idTrabajo', $trabajoId)
            ->get();
            
        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
            'filtroTrabajo' => $trabajoId
        ]);
    }

    /**
     * Obtener pagos por estado
     */
    public function porEstado($estadoId)
    {
        $pagos = Pagos::with(['trabajo', 'estadoPago'])
            ->where('idEstadoPago', $estadoId)
            ->get();
            
        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
            'filtroEstado' => $estadoId
        ]);
    }

    // Checkout múltiple (vista)
    public function checkout(Request $request)
    {
        $ids = collect($request->input('trabajos', []))->filter()->values();
        $trabajos = Trabajos::with(['cliente','servicio', 'detalleTrabajo'])->whereIn('id', $ids)->get();
        $estadoPendiente = EstadoPago::orderBy('id')->first();
        
        return Inertia::render('Pagos/Checkout', [
            'trabajos' => $trabajos,
            'estadoDefault' => $estadoPendiente?->id,
        ]);
    }

    // Crear múltiples pagos (uno por trabajo)
    public function storeMultiple(Request $request)
    {
        $data = $request->validate([
            'trabajos' => 'required|array|min:1',
            'trabajos.*' => 'integer|exists:trabajos,id',
            'aCuenta' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['trabajos'] as $trabajoId) {
                // Calcular automáticamente para cada trabajo
                $datosPago = Pagos::calcularPago($trabajoId, $data['aCuenta']);
                
                Pagos::create([
                    'idTrabajo' => $trabajoId,
                    'total' => $datosPago['total'],
                    'aCuenta' => $datosPago['aCuenta'],
                    'saldo' => $datosPago['saldo'],
                    'idEstadoPago' => $datosPago['idEstadoPago'],
                ]);
            }
        });

        return redirect()->route('pagos')->with('success', 'Pagos registrados correctamente');
    }
}
