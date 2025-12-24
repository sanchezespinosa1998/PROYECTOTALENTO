# Configuración de PHPMailer para el formulario de pre-reserva

## Instalación de PHPMailer

### Opción 1: Usando Composer (Recomendado)

1. Instala Composer si no lo tienes: https://getcomposer.org/download/

2. En la raíz del proyecto, ejecuta:
```bash
composer require phpmailer/phpmailer
```

3. En `enviar-presave.php`, descomenta la línea:
```php
require 'vendor/autoload.php';
```

### Opción 2: Descarga manual

1. Descarga PHPMailer desde: https://github.com/PHPMailer/PHPMailer

2. Extrae la carpeta `PHPMailer` en la raíz de tu proyecto

3. Asegúrate de que la estructura sea:
```
tu-proyecto/
  ├── PHPMailer/
  │   ├── src/
  │   │   ├── Exception.php
  │   │   ├── PHPMailer.php
  │   │   └── SMTP.php
  ├── enviar-presave.php
  └── presave-plaza.html
```

## Configuración de Gmail SMTP

### Paso 1: Habilitar contraseña de aplicación en Gmail

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Activa la verificación en 2 pasos si no la tienes activada
3. Ve a "Seguridad" > "Contraseñas de aplicaciones"
4. Selecciona "Correo" y "Otro (nombre personalizado)"
5. Escribe "Proyecto Talento" y genera la contraseña
6. **Copia la contraseña generada** (16 caracteres sin espacios)

### Paso 2: Configurar el archivo PHP

Edita `enviar-presave.php` y actualiza estas líneas:

```php
$smtp_username = 'tu-email@gmail.com'; // Tu email de Gmail
$smtp_password = 'tu-contraseña-de-aplicacion'; // La contraseña de 16 caracteres que generaste
$smtp_from_email = 'tu-email@gmail.com'; // El mismo email de Gmail
$smtp_from_name = 'Proyecto Talento'; // Nombre que aparecerá como remitente
```

### Ejemplo:
```php
$smtp_username = 'miempresa@gmail.com';
$smtp_password = 'abcd efgh ijkl mnop'; // Sin espacios
$smtp_from_email = 'miempresa@gmail.com';
$smtp_from_name = 'Proyecto Talento';
```

## Configuración alternativa: Otros proveedores SMTP

### Outlook/Hotmail
```php
$smtp_host = 'smtp-mail.outlook.com';
$smtp_port = 587;
```

### Yahoo
```php
$smtp_host = 'smtp.mail.yahoo.com';
$smtp_port = 587;
```

### Servidor propio
```php
$smtp_host = 'smtp.tudominio.com';
$smtp_port = 587; // o 465 para SSL
$smtp_username = 'noreply@tudominio.com';
$smtp_password = 'tu-contraseña';
```

## Verificación

Una vez configurado, prueba el formulario. Si hay errores, revisa:
- Que PHPMailer esté correctamente instalado
- Que las credenciales SMTP sean correctas
- Que el servidor tenga habilitada la función `mail()` o acceso a SMTP
- Revisa los logs de error de PHP

## Notas de seguridad

- **NUNCA** subas el archivo con las contraseñas a un repositorio público
- Considera usar variables de entorno para las credenciales
- El email se enviará a: `sanchesespinosa1998@gmail.com`








