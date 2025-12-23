# Configuración de Google Sheets para Formularios

## Pasos para configurar el envío de formularios a Google Sheets

### 1. Crear una nueva hoja de Google Sheets

1. Ve a [Google Sheets](https://sheets.google.com)
2. Crea una nueva hoja de cálculo
3. En la primera fila, añade estos encabezados (en orden):
   - Nombre
   - Email
   - Teléfono
   - Perfil de LinkedIn
   - Situación Actual
   - Objetivo Profesional (solo para reserva)
   - Mensaje Adicional (solo para reserva)
   - Fecha
   - Origen (para identificar desde qué formulario viene)

### 2. Crear el script de Google Apps Script

1. En tu hoja de Google Sheets, ve a **Extensiones** > **Apps Script**
2. Elimina todo el código que aparece por defecto
3. Copia y pega el siguiente código:

```javascript
function doPost(e) {
  try {
    const sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    const data = JSON.parse(e.postData.contents);
    
    const timestamp = new Date();
    const phoneNumber = (data['phone-prefix'] || '') + ' ' + (data.phone || data.telefono || '');
    const situation = data.situation || data.situacion || '';
    
    const row = [
      data.name || '',
      data.email || '',
      phoneNumber.trim(),
      data.linkedin || '',
      situation,
      data.objetivo || '', // Solo para formulario de reserva
      data.mensaje || '', // Solo para formulario de reserva
      timestamp,
      data.origen || 'Contacto'
    ];
    
    sheet.appendRow(row);
    
    return ContentService
      .createTextOutput(JSON.stringify({ success: true, message: 'Datos guardados correctamente' }))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({ success: false, error: error.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

function doGet(e) {
  return ContentService
    .createTextOutput('Este endpoint solo acepta POST requests')
    .setMimeType(ContentService.MimeType.TEXT);
}
```

### 3. Desplegar como Web App

1. En el editor de Apps Script, haz clic en **Desplegar** > **Nueva implementación**
2. Selecciona **Tipo**: Aplicación web
3. Configura:
   - **Descripción**: Formulario de contacto
   - **Ejecutar como**: Yo (tu email)
   - **Quién tiene acceso**: Cualquiera
4. Haz clic en **Desplegar**
5. **IMPORTANTE**: Copia la URL que aparece (algo como: `https://script.google.com/macros/s/AKfycby.../exec`)
6. Esta URL es la que usarás en los formularios

### 4. Configurar los formularios

La URL del Web App debe insertarse en los archivos HTML:

1. **index.html**: Busca la línea que dice `const GOOGLE_SCRIPT_URL = 'TU_URL_DE_GOOGLE_APPS_SCRIPT_AQUI';` y reemplaza `TU_URL_DE_GOOGLE_APPS_SCRIPT_AQUI` con tu URL del Web App.

2. **reservar-plaza.html**: Busca la misma línea y reemplázala con tu URL del Web App.

**Ejemplo:**
```javascript
const GOOGLE_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycby.../exec';
```

### Notas importantes

- La primera vez que alguien envíe el formulario, Google pedirá autorización. Debes autorizar el script.
- La URL del Web App es pública pero segura, solo permite escribir en tu hoja.
- Los datos se guardarán automáticamente en la hoja cuando alguien envíe el formulario.

