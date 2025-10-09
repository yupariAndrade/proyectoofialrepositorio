<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - FOTO STUDIO EU</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            color: #333;
        }
        
        .recibo {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 15px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .logo {
            max-width: 80px;
            height: auto;
            margin-bottom: 5px;
        }
        
        .empresa {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .info-cliente {
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        
        .info-cliente p {
            margin: 3px 0;
            font-size: 11px;
        }
        
        .servicios {
            margin-bottom: 15px;
        }
        
        .servicios h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: bold;
        }
        
        .servicio {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .totales {
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        
        .totales p {
            margin: 3px 0;
            font-size: 11px;
        }
        
        .total-final {
            font-weight: bold;
            font-size: 12px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            color: #666;
        }
        
        .fecha {
            font-size: 9px;
            color: #888;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="recibo">
        <!-- Header con logo -->
        <div class="header">
            <img src="{{ public_path('img/logo.png') }}" alt="FOTO STUDIO EU" class="logo">
            <div class="empresa">FOTO STUDIO EU</div>
        </div>
        
        <!-- Información del cliente -->
        <div class="info-cliente">
            <p><strong>Cliente:</strong> {{ $trabajo->cliente->nombre ?? 'Sin cliente' }} {{ $trabajo->cliente->apellido ?? '' }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($trabajo->fechaRegistro)->format('d/m/Y') }}</p>
            <p><strong>Entrega:</strong> {{ \Carbon\Carbon::parse($trabajo->fechaEntrega)->format('d/m/Y') }}</p>
        </div>
        
        <!-- Servicios -->
        <div class="servicios">
            <h4>Servicios:</h4>
            @foreach($servicios as $servicio)
                <div class="servicio">
                    • {{ $servicio['nombre'] }} x{{ $servicio['cantidad'] }} - {{ number_format($servicio['subtotal'], 2) }} Bs
                </div>
            @endforeach
        </div>
        
        <!-- Totales -->
        <div class="totales">
            <p><strong>Total:</strong> {{ number_format($total, 2) }} Bs</p>
            <p><strong>A Cuenta:</strong> {{ number_format($aCuenta, 2) }} Bs</p>
            <p><strong>Saldo:</strong> {{ number_format($saldo, 2) }} Bs</p>
            <p><strong>Estado:</strong> {{ $trabajo->estadoPago->nombre ?? 'Sin estado' }}</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>¡Gracias por su preferencia!</p>
            <div class="fecha">
                Recibo generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</body>
</html>




