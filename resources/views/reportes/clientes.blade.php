<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Clientes - FOTO STUDIO EU</title>
  <style>
    /* Configuración de impresión */
    @page {
      size: A4 landscape;
      margin: 2cm;
    }

    body {
      font-family: 'Arial', sans-serif;
      font-size: 14.67px;
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
      font-size: 14.67px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    th {
      background: #dc2626;
      color: white;
      font-size: 14.67px;
      font-weight: bold;
      text-transform: uppercase;
      padding: 10px;
      text-align: center;
    }

    td {
      font-size: 14.67px;
      color: #374151;
      padding: 8px;
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

    .client-name {
      font-weight: 600;
      color: #1f2937;
      font-size: 14.67px;
    }

    .client-info {
      color: #6b7280;
      font-size: 13px;
      margin-top: 2px;
    }

    .contact-info {
      color: #374151;
      font-size: 13px;
    }

    .email {
      color: #dc2626;
      font-weight: 500;
    }

    .phone {
      color: #059669;
      font-weight: 500;
    }

    .ci {
      background-color: #f3f4f6;
      color: #374151;
      padding: 4px 8px;
      border-radius: 6px;
      font-family: monospace;
      font-size: 12px;
      font-weight: 600;
    }

    .date {
      color: #9ca3af;
      font-size: 13px;
    }

    .responsible {
      font-weight: 500;
      color: #374151;
      font-size: 13px;
    }

    /* Footer */
    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 13px;
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

    .no-data {
      text-align: center;
      padding: 40px;
      color: #9ca3af;
      font-style: italic;
      font-size: 14.67px;
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
      <h2>Lista de Clientes Registrados</h2>
      <small>Generado el {{ $fechaGeneracion }}</small>
    </div>
  </div>

  <!-- Tabla de clientes -->
  @if($clientes->count() > 0)
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Cédula</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Fecha Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($clientes as $cliente)
      <tr>
        <td>
          <div class="client-name">{{ $cliente->nombre }}</div>
        </td>
        <td>
          <div class="client-name">{{ $cliente->apellido }}</div>
        </td>
        <td>
          <span class="ci">{{ $cliente->ci ?? 'N/A' }}</span>
        </td>
        <td class="contact-info">
          @if($cliente->telefono)
            <div class="phone">{{ $cliente->telefono }}</div>
          @else
            <div style="color: #9ca3af; font-style: italic;">N/A</div>
          @endif
        </td>
        <td class="contact-info">
          @if($cliente->correoElectronico)
            <div class="email">{{ $cliente->correoElectronico }}</div>
          @else
            <div style="color: #9ca3af; font-style: italic;">N/A</div>
          @endif
        </td>
        <td class="date">{{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="no-data">
    <p>No hay clientes registrados en el sistema</p>
  </div>
  @endif

  <!-- Footer -->
  <div class="footer">
    <p>© {{ date('Y') }} FOTO STUDIO EU - Todos los derechos reservados</p>
  </div>
</body>
</html>
