# Moviespace 

Moviespace es una aplicación web desarrollada con **Laravel** bajo la arquitectura Modelo-Vista-Controlador (MVC). Su objetivo principal es resolver los problemas logísticos y financieros que enfrentan los negocios dedicados al alquiler de películas en formato físico (CD).

El sistema automatiza el control de existencias en estante, gestiona de forma segura el registro de clientes, calcula penalizaciones por retrasos en tiempo real y ofrece un módulo completo de auditoría de caja y generación de reportes en PDF.

---

##  Características Principales

* **Autenticación y Roles (RBAC):** Acceso diferenciado mediante middlewares de seguridad para el Administrador (Dueño), Trabajador (Encargado) y los Usuarios (Clientes).
* **Gestión de Inventario Inteligente:** Monitoreo dinámico del stock físico basado en estados automáticos (`Disponible`, `Alquilado / Agotado` y `Próximamente`).
* **Buscador en Tiempo Real:** Barra de búsqueda optimizada para verificar la disponibilidad inmediata de copias físicas en estante.
* **Módulo de Préstamos y Devoluciones:** Automatización del flujo logístico de salida y retorno de material físico.
* **Gestión de Pagos y Caja Chica:** Control estricto de ingresos por alquileres bases y cálculo automático de cobro de multas acumuladas por días de retraso.
* **Reportes PDF:** Generación de balances financieros mensuales y reportes de pérdidas materiales listos para impresión utilizando vistas Blade.

---

##  Matriz de Roles y Permisos (RBAC)

El acceso a las rutas y operaciones críticas del sistema está protegido mediante *Middlewares* de Laravel, segmentando las capacidades del sistema en tres perfiles:

| Funcionalidad / Pantalla                        | Rol: Administrador (Dueño) | Rol: Trabajador (Encargado) |  Rol: Usuario (Cliente)  |
| :---------------------------------------------- | :------------------------: | :-------------------------: | :----------------------: |
| Ver catálogo público y existencias              |             Sí             |              Sí             |            Sí            |
| Buscador dinámico de títulos en tiempo real     |             Sí             |              Sí             | Sí (Solo disponibilidad) |
| Auto-registro desde el sitio web público        |             No             |              No             |          **Sí**          |
| Registro de cuentas de Trabajadores             |           **Sí**           |              No             |            No            |
| Registro, edición y baja de películas (CRUD)    |             Sí             |              No             |            No            |
| Control de préstamos y devoluciones (Logística) |             Sí             |            **Sí**           |            No            |
| Gestión de pagos, cajas y cobro de multas       |             Sí             |            **Sí**           |            No            |
| Visualización de alertas de retraso             |             Sí             |              Sí             |            No            |
| Exportación de reportes contables en PDF        |           **Sí**           |              No             |            No            |

---

##  Lógica de Negocio y Reglas de Control Estrictas

### 1. Flujo de Registro y Atención al Cliente

* **Auto-registro Exclusivo:** El formulario de registro público del sitio web está habilitado única y exclusivamente para los clientes.
* **Política de Atención en Sucursal:** Si un ciudadano acude a las instalaciones físicas a solicitar un alquiler y no posee una cuenta en el sistema, el personal encargado (Trabajador o Administrador) le solicitará que ingrese a la plataforma desde su dispositivo móvil para efectuar su registro antes de poder procesar cualquier préstamo.
* **Alta de Personal:** Las credenciales y cuentas de los Trabajadores sólo pueden ser creadas de manera interna por el Administrador.

### 2. Regla de Bloqueo Preventivo de Alquileres

Para erradicar la pérdida de material y asegurar el retorno de los discos, el sistema implementa un bloqueo por morosidad en el backend:

* **Restricción de 1 Película por Cliente:** Un cliente no podrá alquilar una nueva película si posee un préstamo activo en su historial. Antes de guardar la transacción, el sistema validará que el usuario no tenga copias físicas pendientes de devolución. Si tiene un pendiente, el sistema bloqueará el proceso y emitirá una alerta visual exigiendo el retorno del CD previo.

---

##  Estructura de la Base de Datos (Tablas Clave)

El sistema utiliza las siguientes entidades y relaciones para mantener la integridad física del inventario y el dinero de caja:

1. **`users`:** Almacena la información de contacto (DUI, teléfono, email, contraseña) y controla los accesos con el campo `rol` (`admin`, `trabajador`, `cliente`).

2. **`peliculas`:** Centraliza el catálogo de títulos, sinopsis, géneros, precio de alquiler base, `copias_totales` y `copias_en_estante`.

3. **`prestamos`:** Cabecera de la transacción de alquiler. Enlaza al cliente (`id_usuario`), al empleado (`id_trabajador`), registra las fechas (`fecha_salida`, `fecha_limite`, `fecha_entrega_real`) y el `estado_prestamo`.

4. **`detalle_prestamos`:** Tabla intermedia que rompe la relación de muchos a muchos entre préstamos y películas, almacenando de forma aislada el `monto_multa` correspondiente a cada CD físico.

5. **`pagos`:** Registro transaccional de caja chica. Almacena el `monto`, `concepto` (`alquiler`, `multa`), el `metodo_pago` utilizado (efectivo, tarjeta, transferencia) y el empleado que procesó el dinero.

---

#  Instalación Local de Moviespace

Si deseas ejecutar este proyecto en tu computadora localmente, sigue cuidadosamente los siguientes pasos.

---

##  Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

* PHP 8.2 o superior
* Composer
* Node.js y npm
* MySQL, MariaDB o SQL Server
* Git
* Laravel CLI (opcional)

Puedes verificar las versiones instaladas con:

```bash
php -v
composer -V
node -v
npm -v
git --version
```

---

## 1️ Clonar el Repositorio

Abre una terminal y ejecuta:

```bash
git clone <URL_DE_ESTE_REPOSITORIO>
cd Moviespace
```

---

## 2️ Instalar Dependencias de Laravel

Instala todas las dependencias de PHP utilizando Composer:

```bash
composer install
```

---

## 3️ Crear el Archivo `.env`

Laravel utiliza un archivo de entorno para manejar configuraciones locales.

### En Linux / macOS

```bash
cp .env.example .env
```

### En Windows (CMD)

```cmd
copy .env.example .env
```

---

## 4️ Configurar la Base de Datos

Abre el archivo `.env` y configura tus credenciales locales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moviespace
DB_USERNAME=root
DB_PASSWORD=
```

>  Asegúrate de haber creado previamente la base de datos `moviespace` desde phpMyAdmin, MySQL Workbench o tu gestor preferido.

---

## 5️ Generar la Clave de la Aplicación

Laravel necesita una clave de seguridad única.

Ejecuta:

```bash
php artisan key:generate
```

---

## 6️ Ejecutar las Migraciones

Crea automáticamente todas las tablas del sistema:

```bash
php artisan migrate
```

---

## 7️ Instalar Dependencias Frontend

Instala las dependencias de JavaScript y CSS:

```bash
npm install
```

---

## 8️ Ejecutar Vite

Levanta el compilador frontend para los recursos visuales:

```bash
npm run dev
```

---

## 9️ Iniciar el Servidor Local

En otra terminal, inicia el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

---

##  Acceder al Proyecto

Abre tu navegador y entra a:

```txt
http://127.0.0.1:8000
```

---

## 📌 Notas Importantes

* El sistema utiliza Laravel MVC.
* Las vistas están desarrolladas con Blade.
* Los reportes PDF son generados desde el backend.
* El control de acceso utiliza Middlewares y RBAC.
* El frontend es compilado con Vite.

---

##  Tecnologías Utilizadas

* Laravel
* PHP
* MySQL
* Blade
* Vite
* JavaScript
* HTML5
* CSS3

---


