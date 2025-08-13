# 📸 API Sistema de FotoEstudio

## 🚀 Endpoints Disponibles

### 🔐 Autenticación
Todos los endpoints requieren autenticación con middleware `auth` y `verified`.

---

## 👥 **Roles**
- `GET /api/roles` - Listar todos los roles
- `POST /api/roles` - Crear nuevo rol
- `GET /api/roles/{id}` - Obtener rol específico
- `PUT /api/roles/{id}` - Actualizar rol
- `DELETE /api/roles/{id}` - Eliminar rol

**Ejemplo POST:**
```json
{
    "nombre": "Fotógrafo"
}
```

---

## 👤 **Usuarios**
- `GET /api/usuarios` - Listar todos los usuarios
- `POST /api/usuarios` - Crear nuevo usuario
- `GET /api/usuarios/{id}` - Obtener usuario específico
- `PUT /api/usuarios/{id}` - Actualizar usuario
- `DELETE /api/usuarios/{id}` - Eliminar usuario
- `GET /api/usuarios/exportar/pdf` - Exportar usuarios a PDF

**Ejemplo POST:**
```json
{
    "nombre": "Juan",
    "apellidoPaterno": "Pérez",
    "apellidoMaterno": "García",
    "ci": "12345678",
    "telefono": "70012345",
    "direccion": "Av. Principal 123",
    "email": "juan@fotoestudio.com",
    "fechaIngreso": "2024-01-15",
    "estado": true,
    "idRol": 1
}
```

---

## 🎯 **Servicios**
- `GET /api/servicios` - Listar todos los servicios
- `POST /api/servicios` - Crear nuevo servicio
- `GET /api/servicios/{id}` - Obtener servicio específico
- `PUT /api/servicios/{id}` - Actualizar servicio
- `DELETE /api/servicios/{id}` - Eliminar servicio

**Ejemplo POST:**
```json
{
    "nombreServicio": "Fotos de Boda",
    "precioReferencial": 1500.00,
    "descripcion": "Sesión completa de fotos de boda",
    "estado": true,
    "imagenReferencia": "boda.jpg",
    "idUsuario": 1
}
```

---

## 👥 **Clientes**
- `GET /api/clientes` - Listar todos los clientes
- `POST /api/clientes` - Crear nuevo cliente
- `GET /api/clientes/{id}` - Obtener cliente específico
- `PUT /api/clientes/{id}` - Actualizar cliente
- `DELETE /api/clientes/{id}` - Eliminar cliente

**Ejemplo POST:**
```json
{
    "nombre": "María",
    "apellido": "López",
    "ci": "87654321",
    "telefono": "70087654",
    "correoElectronico": "maria@email.com",
    "idUsuario": 1
}
```

---

## 📋 **Estados de Trabajo**
- `GET /api/estados-trabajo` - Listar todos los estados
- `POST /api/estados-trabajo` - Crear nuevo estado
- `GET /api/estados-trabajo/{id}` - Obtener estado específico
- `PUT /api/estados-trabajo/{id}` - Actualizar estado
- `DELETE /api/estados-trabajo/{id}` - Eliminar estado

**Ejemplo POST:**
```json
{
    "nombre": "En Proceso"
}
```

---

## 💰 **Estados de Pago**
- `GET /api/estados-pago` - Listar todos los estados
- `POST /api/estados-pago` - Crear nuevo estado
- `GET /api/estados-pago/{id}` - Obtener estado específico
- `PUT /api/estados-pago/{id}` - Actualizar estado
- `DELETE /api/estados-pago/{id}` - Eliminar estado

**Ejemplo POST:**
```json
{
    "nombre": "Pendiente"
}
```

---

## 🎯 **Trabajos**
- `GET /api/trabajos` - Listar todos los trabajos
- `POST /api/trabajos` - Crear nuevo trabajo
- `GET /api/trabajos/{id}` - Obtener trabajo específico
- `PUT /api/trabajos/{id}` - Actualizar trabajo
- `DELETE /api/trabajos/{id}` - Eliminar trabajo
- `GET /api/trabajos/estado/{estadoId}` - Trabajos por estado
- `GET /api/trabajos/cliente/{clienteId}` - Trabajos por cliente

**Ejemplo POST:**
```json
{
    "idCliente": 1,
    "idServicio": 1,
    "idUsuario": 1,
    "fechaRegistro": "2024-01-20",
    "fechaEntrega": "2024-01-25",
    "idEstado": 1
}
```

---

## 📝 **Detalles de Trabajo**
- `GET /api/detalles-trabajo` - Listar todos los detalles
- `POST /api/detalles-trabajo` - Crear nuevo detalle
- `GET /api/detalles-trabajo/{id}` - Obtener detalle específico
- `PUT /api/detalles-trabajo/{id}` - Actualizar detalle
- `DELETE /api/detalles-trabajo/{id}` - Eliminar detalle
- `GET /api/detalles-trabajo/trabajo/{trabajoId}` - Detalles por trabajo

**Ejemplo POST:**
```json
{
    "idTrabajo": 1,
    "descripcion": "Fotos 15x20",
    "tamano": "15x20",
    "color": "Color",
    "modelo": "Estándar",
    "cantidad": 50,
    "tipoDocumento": "Boda",
    "tipoEvento": "Ceremonia",
    "otros": "Incluir álbum"
}
```

---

## 👨‍💼 **Asignaciones de Trabajo**
- `GET /api/asignaciones-trabajo` - Listar todas las asignaciones
- `POST /api/asignaciones-trabajo` - Crear nueva asignación
- `GET /api/asignaciones-trabajo/{id}` - Obtener asignación específica
- `PUT /api/asignaciones-trabajo/{id}` - Actualizar asignación
- `DELETE /api/asignaciones-trabajo/{id}` - Eliminar asignación
- `GET /api/asignaciones-trabajo/trabajo/{trabajoId}` - Asignaciones por trabajo
- `GET /api/asignaciones-trabajo/usuario/{usuarioId}` - Asignaciones por usuario

**Ejemplo POST:**
```json
{
    "idTrabajo": 1,
    "idUsuarioEncargado": 2,
    "turno": "Mañana",
    "fechaAsignacion": "2024-01-20"
}
```

---

## 📊 **Bitácora de Trabajos**
- `GET /api/bitacora-trabajos` - Listar toda la bitácora
- `POST /api/bitacora-trabajos` - Crear nuevo registro
- `GET /api/bitacora-trabajos/{id}` - Obtener registro específico
- `PUT /api/bitacora-trabajos/{id}` - Actualizar registro
- `DELETE /api/bitacora-trabajos/{id}` - Eliminar registro
- `GET /api/bitacora-trabajos/trabajo/{trabajoId}` - Bitácora por trabajo
- `GET /api/bitacora-trabajos/usuario/{usuarioId}` - Bitácora por usuario

**Ejemplo POST:**
```json
{
    "idTrabajo": 1,
    "idUsuario": 2,
    "accion": "Iniciado",
    "fecha": "2024-01-20 09:00:00",
    "descripcion": "Se inició el trabajo de fotos de boda"
}
```

---

## 💰 **Pagos**
- `GET /api/pagos` - Listar todos los pagos
- `POST /api/pagos` - Crear nuevo pago
- `GET /api/pagos/{id}` - Obtener pago específico
- `PUT /api/pagos/{id}` - Actualizar pago
- `DELETE /api/pagos/{id}` - Eliminar pago
- `GET /api/pagos/trabajo/{trabajoId}` - Pagos por trabajo
- `GET /api/pagos/estado/{estadoId}` - Pagos por estado

**Ejemplo POST:**
```json
{
    "idTrabajo": 1,
    "monto": 1500.00,
    "fecha": "2024-01-20",
    "comentario": "Pago inicial",
    "idEstadoPago": 1
}
```

---

## 🔧 **Respuestas de Error**

Todos los endpoints devuelven errores de validación en formato JSON:

```json
{
    "errores": {
        "campo": ["El campo es requerido"]
    }
}
```

## ✅ **Respuestas de Éxito**

- **Crear**: Status 201 con el objeto creado
- **Actualizar/Eliminar**: Status 200 con mensaje de confirmación
- **Listar/Obtener**: Status 200 con los datos

---

## 🎯 **Funcionalidades Especiales**

### 📊 **Reportes**
- Exportación de usuarios a PDF
- Filtros por estado, cliente, usuario
- Bitácora de actividades

### 🔍 **Consultas Avanzadas**
- Trabajos por estado
- Pagos por trabajo
- Asignaciones por usuario
- Bitácora cronológica

---

## 🚀 **Próximos Pasos**

1. **Frontend**: Crear interfaces con Inertia.js
2. **Validaciones**: Mejorar reglas de negocio
3. **Reportes**: Agregar más tipos de reportes
4. **Notificaciones**: Sistema de alertas
5. **Dashboard**: Estadísticas en tiempo real
