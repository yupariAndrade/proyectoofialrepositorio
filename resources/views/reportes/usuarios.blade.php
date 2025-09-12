<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Usuarios - FOTO STUDIO EU</title>
  <style>
    @page {
      size: A4 landscape;
      margin: 2cm;
    }

    body {
      font-family: 'Arial', sans-serif;
      font-size: 12px;
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
      font-size: 12px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    th {
      background: #dc2626;
      color: white;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
      padding: 8px;
      text-align: center;
    }

    td {
      font-size: 12px;
      color: #374151;
      padding: 6px;
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

    /* Badges */
    .status-active {
      background: #10b981;
      color: white;
      padding: 2px 6px;
      border-radius: 8px;
      font-size: 10px;
      font-weight: bold;
    }

    .status-inactive {
      background: #ef4444;
      color: white;
      padding: 2px 6px;
      border-radius: 8px;
      font-size: 10px;
      font-weight: bold;
    }

    .role-badge {
      background: #f59e0b;
      color: white;
      padding: 2px 6px;
      border-radius: 8px;
      font-size: 10px;
      font-weight: bold;
    }

    /* Footer */
    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 11px;
      color: #6b7280;
      border-top: 1px solid #dc2626;
      padding-top: 10px;
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
  <div class="header">
    <div class="logo">
      <img src="{{ public_path('img/logo.png') }}" alt="Logo FOTO STUDIO EU">
      <div class="logo-text">
        <h1>FOTO STUDIO EU</h1>
        <p>Hace que la magia suceda</p>
      </div>
    </div>

    <div class="header-title">
      <h2>Lista de Usuarios Registrados</h2>
      <small>Generado el {{ $fechaGeneracion }}</small>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nombre Completo</th>
        <th>Gmail</th>
        <th>Dirección</th>
        <th>Número</th>
        <th>CI</th>
        <th>Rol</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      @foreach($usuarios as $usuario)
      <tr>
        <td>
          {{ $usuario->nombre }}
          @if($usuario->apellidoPaterno || $usuario->apellidoMaterno)
            <br><span style="font-size:11px; color:#6b7280;">
              {{ $usuario->apellidoPaterno ?? '' }} {{ $usuario->apellidoMaterno ?? '' }}
            </span>
          @endif
        </td>
        <td>{{ $usuario->email ?? 'N/A' }}</td>
        <td>{{ $usuario->direccion ?? 'N/A' }}</td>
        <td>{{ $usuario->telefono ?? 'N/A' }}</td>
        <td>{{ $usuario->ci ?? 'N/A' }}</td>
        <td>
          <span class="role-badge">{{ $usuario->rol ? $usuario->rol->nombre : 'Sin rol' }}</span>
        </td>
        <td>
          @if($usuario->estado)
            <span class="status-active">Activo</span>
          @else
            <span class="status-inactive">Inactivo</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="footer">
    <p>© {{ date('Y') }} FOTO STUDIO EU - Todos los derechos reservados</p>
  </div>
</body>
</html>

