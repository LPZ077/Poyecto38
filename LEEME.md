# Chispa – Plataforma de empleo juvenil (INFRAMEN)
PHP 8.1+ · MySQL · HTML · CSS · JavaScript (XAMPP)

## Instalación
1. Copia la carpeta `chispa` a `C:\xampp\htdocs\`.
2. Abre XAMPP e inicia **Apache** y **MySQL**.
3. En http://localhost/phpmyadmin → Importar → elige `database/chispa.sql`.
4. Abre http://localhost/chispa/instalar.php (crea datos demo) y luego **borra instalar.php**.
5. Entra en http://localhost/chispa/

Usuarios demo (clave `Chispa2026`): empresa@chispa.test · tecno@chispa.test · estudiante@chispa.test
Si tu MySQL tiene contraseña, cámbiala en `includes/config.php`.

## Qué incluye
- Inicio de sesión, registro (estudiante / empleador), cierre de sesión.
- CRUD: vacantes (crear, editar, cerrar, eliminar), postulaciones (crear, retirar), perfil.
- Búsqueda y filtros por texto, zona, horario y área; estadísticas en portada y paneles.
- Correo al aceptar una postulación: se guarda en la tabla `correos` y se ve en "Mis correos"; el empleador ve la "Vista previa del correo". Si configuras SMTP en XAMPP (sendmail), también sale por `mail()`.
- Responsive para móvil. Seguridad: contraseñas con hash, consultas preparadas (PDO), token CSRF, control de roles, límite de intentos de login, escape de HTML.
