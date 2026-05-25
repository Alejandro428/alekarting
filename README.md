# AleKarting

> Plataforma web completa para la gestión de un circuito de karting: reservas online, eventos, calendario, pagos con Stripe y panel de administración por roles.

**Proyecto Final del Grado Superior en Desarrollo de Aplicaciones Web (DAW) — FP Solvam, 2025.**

---

## Sobre el proyecto

AleKarting es una aplicación web pensada para que un circuito de karting pueda **gestionar todo su negocio desde una única plataforma**: que los clientes reserven carreras y eventos online y paguen, que los empleados gestionen el día a día, y que el administrador tenga el control global.

El proyecto cubre el ciclo completo: desde el registro de un cliente hasta el pago confirmado de una reserva, pasando por la verificación de disponibilidad en tiempo real sobre el calendario.

## Funcionalidades principales

### Cliente
- Registro y login con validaciones
- Reserva online de **carreras** y **eventos** con verificación de disponibilidad
- Pago integrado con **Stripe** (pasarela completa)
- Historial de reservas (carreras y eventos)
- Gestión de perfil y credenciales
- Sección de noticias del circuito con seguimiento de popularidad por visitas

### Empleado
- Panel propio con dashboard
- Gestión de **noticias** (crear, editar, eliminar)
- Gestión de **eventos**: creación, edición, control de horarios y reservas
- Visualización de clientes inscritos en cada evento
- Cambio de credenciales propias

### Administrador
- Panel de control global
- Gestión de empleados, usuarios y operaciones del sistema
- Configuración de pistas, categorías y tipos de evento

### Calendario
- Vista de días con reservas (carreras y eventos)
- Conteo de reservas por día y por franja horaria
- Vista mensual de eventos

## Stack técnico

| Capa | Tecnología |
|---|---|
| Backend | **PHP 8.1+** con **CodeIgniter 4** (MVC) |
| Frontend | HTML5, CSS3, JavaScript, **jQuery**, Bootstrap |
| Base de datos | **MySQL** (modelos con CodeIgniter Query Builder) |
| Pagos | **Stripe API** (modo test) |
| Autenticación | Sesiones con control de acceso por roles |
| Email | Servicio de correo de CodeIgniter |
| Otros | Composer, JWT, Routing CodeIgniter 4 |

## Arquitectura

Aplicación **MVC clásica** con CodeIgniter 4:

```
app/
  Config/          # Configuración (rutas, BD, Stripe, sesiones)
  Controllers/     # 24 controllers (Admin, Usuario, Empleado, Carreras...)
  Models/          # 16 modelos (uno por entidad principal)
  Views/           # Vistas por sección (admin, empleado, usuario, etc.)
  Filters/         # Filtros de autenticación y autorización
public/            # index.php + assets públicos
writable/          # Cache, logs, sessions, uploads (no versionado)
```

## Cómo correrlo en local

### Requisitos
- PHP **8.1+** con extensiones `intl`, `mbstring`, `mysqlnd`
- **Composer**
- **MySQL** (XAMPP, Laragon o servidor independiente)
- Cuenta de **Stripe** en modo test (gratis) para la pasarela de pagos

### Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/Alejandro428/alekarting.git
cd alekarting

# 2. Instalar dependencias PHP
composer install

# 3. Crear el archivo de configuración local
cp .env.example .env

# 4. Editar .env con tus datos:
#    - Datos de conexión a MySQL
#    - Tus claves de Stripe (test)
#    - JWT_SECRET (cadena aleatoria larga)

# 5. Importar la base de datos
#    (Pendiente: añadir alekarting.sql con el esquema)

# 6. Arrancar el servidor de desarrollo
php spark serve
```

Acceder a `http://localhost:8080`.

## Seguridad

- Las claves de Stripe y demás secretos **se leen exclusivamente desde `.env`** (nunca hardcodeadas en el código).
- `.env` está incluido en `.gitignore`; solo se versiona `.env.example` como plantilla.
- Las claves usadas durante el desarrollo fueron rotadas antes de publicar el repositorio.

## Capturas

*(Próximamente: pantalla de inicio, panel de reservas, calendario, pasarela de pago, panel admin)*

## Autor

**Alejandro Jiménez Cabrera** — Desarrollador Web Junior, Valencia
- GitHub: [@Alejandro428](https://github.com/Alejandro428)
- Email: alejandrojimenez4286@gmail.com

## Licencia

Proyecto educativo desarrollado como Trabajo Final del Grado Superior DAW.
Código bajo licencia MIT — ver [LICENSE](LICENSE) para más detalles.
