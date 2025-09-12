<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Servicios - FOTO STUDIO EU</title>
  <style>
    /* Configuración de impresión */
    @page {
      size: A4 landscape;
      margin: 2cm;
    }

    body {
      font-family: 'Arial', sans-serif;
      font-size: 13px;
      margin: 0;
      padding: 0;
      background-color: #f9fafb;
      color: #333;
    }

    /* Encabezado */
    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(135deg, #111827, #1f2937);
      padding: 12px 20px;
      border-radius: 8px;
      border: 2px solid #dc2626;
      color: white;
      margin-bottom: 15px;
      min-height: 1.8cm;
      box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      width: 72px;
      height: 72px;
      object-fit: contain;
      border-radius: 8px;
      border: 2px solid #dc2626;
      background: white;
      padding: 3px;
      margin-right: 12px;
    }

    .logo-text h1 {
      margin: 0;
      font-size: 20px;
      color: #dc2626;
      font-weight: bold;
    }

    .logo-text p {
      margin: 2px 0 0;
      font-size: 12px;
      color: #d1d5db;
      font-style: italic;
    }

    .header-title {
      text-align: right;
    }

     .header-title h2 {
       margin: 0;
       font-size: 28px;
       font-weight: bold;
       text-transform: uppercase;
       background: linear-gradient(135deg, #dc2626, #991b1b);
       -webkit-background-clip: text;
       -webkit-text-fill-color: transparent;
       background-clip: text;
       color: #dc2626;
     }

    .header-title small {
      font-size: 12px;
      color: #d1d5db;
      display: block;
      margin-top: 2px;
    }
    /* Tabla */
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      font-size: 15px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    th {
      background: #dc2626;
      color: white;
      font-size: 15px;
      font-weight: bold;
      text-transform: uppercase;
      padding: 12px;
      text-align: center;
    }

    td {
      font-size: 15px;
      color: #374151;
      padding: 10px;
      border-bottom: 1px solid #e5e7eb;
      text-align: center;
    }

    td:first-child {
      text-align: left;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f9fafb;
    }

    tr:hover {
      background-color: #fef2f2;
    }

    /* Etiquetas */
    .status-available {
      background: #10b981;
      color: white;
      padding: 4px 8px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: bold;
    }

    .status-unavailable {
      background: #ef4444;
      color: white;
      padding: 4px 8px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: bold;
    }

    .service-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
      border: 2px solid #e5e7eb;
    }

    .image-placeholder {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
      border-radius: 6px;
      border: 2px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      color: #9ca3af;
      margin: 0 auto;
    }

    .price {
      font-weight: bold;
      color: #059669;
      font-size: 15px;
    }

    .service-name {
      font-weight: 600;
      color: #1f2937;
      font-size: 15px;
    }

    .description {
      color: #6b7280;
      font-size: 14px;
      line-height: 1.3;
      text-align: left;
    }

    .date {
      color: #9ca3af;
      font-size: 14px;
    }

    /* Footer */
    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
      color: #6b7280;
      border-top: 1px solid #dc2626;
      padding-top: 10px;
    }

    .footer p {
      margin: 2px 0;
    }

    .footer .tagline {
      margin-top: 5px;
      font-style: italic;
      color: #dc2626;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Encabezado con logo e info -->
  <div class="header">
    <div class="logo">
      <img src="{{ public_path('img/logo.png') }}" alt="Logo FOTO STUDIO EU">
      <div class="logo-text">
        <h1>FOTO STUDIO EU</h1>
        <p>Hace que la magia suceda</p>
      </div>
    </div>
    <!-- Header info modificado para Dompdf -->
    <div class="header-title">
      <h2>Lista de Servicios Registrados</h2>
      <small>Generado el {{ $fechaGeneracion }}</small>
    </div>
  </div>

  <!-- Tabla de servicios -->
  <table>
    <thead>
      <tr>
        <th>Imagen</th>
        <th>Servicio</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      @foreach($servicios as $servicio)
      <tr>
        <td>
          @if($servicio->imagenReferencia)
            <img src="{{ public_path('storage/' . $servicio->imagenReferencia) }}" 
                 alt="{{ $servicio->nombreServicio }}" 
                 class="service-image">
          @else
            <div class="image-placeholder">Sin img</div>
          @endif
        </td>
        <td class="service-name">{{ $servicio->nombreServicio }}</td>
        <td class="description">{{ $servicio->descripcion ?? 'Sin descripción' }}</td>
        <td class="price">{{ number_format($servicio->precioReferencial, 2) }} Bs</td>
        <td>
          @if($servicio->estado)
            <span class="status-available">Disponible</span>
          @else
            <span class="status-unavailable">No disponible</span>
          @endif
        </td>
        <td class="date">{{ \Carbon\Carbon::parse($servicio->created_at)->format('d/m/Y') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Footer -->
  <div class="footer">
    <p>© {{ date('Y') }} FOTO STUDIO EU - Todos los derechos reservados</p>
  </div>
</body>
</html>