<?php
// Configuración de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Configuración SMTP de Gmail
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = 'israelgomezhrs@gmail.com'; // Tu email de Gmail
$smtp_password = ''; // CONTRASEÑA DE APLICACIÓN DE GMAIL (16 caracteres sin espacios)
$smtp_from_email = 'israelgomezhrs@gmail.com';
$smtp_from_name = 'Proyecto Talento';

// Email de destino
$destinatario = 'israelgomezhrs@gmail.com';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos del formulario
$nombre = isset($_POST['name']) ? trim($_POST['name']) : (isset($_POST['nombre']) ? trim($_POST['nombre']) : '');
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$telefono_prefijo = isset($_POST['phone-prefix']) ? trim($_POST['phone-prefix']) : '';
$telefono = isset($_POST['phone']) ? trim($_POST['phone']) : (isset($_POST['telefono']) ? trim($_POST['telefono']) : '');
$linkedin = isset($_POST['linkedin']) ? trim($_POST['linkedin']) : '';
$situacion = isset($_POST['situation']) ? trim($_POST['situation']) : (isset($_POST['situacion']) ? trim($_POST['situacion']) : '');
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
$objetivo = isset($_POST['objetivo']) ? trim($_POST['objetivo']) : '';

// Validar campos requeridos
if (empty($nombre) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre y email son campos requeridos']);
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email no válido']);
    exit;
}

// Construir el teléfono completo
$telefono_completo = '';
if (!empty($telefono_prefijo) && !empty($telefono)) {
    $telefono_completo = $telefono_prefijo . ' ' . $telefono;
} elseif (!empty($telefono)) {
    $telefono_completo = $telefono;
}

// Mapear situación a texto legible
$situaciones = [
    'estudiante' => 'Estudiante',
    'recien-graduado' => 'Recién graduado',
    'profesional-activo' => 'Profesional en activo',
    'desempleo' => 'Situación de desempleo'
];
$situacion_texto = isset($situaciones[$situacion]) ? $situaciones[$situacion] : $situacion;

// Determinar el tipo de formulario
$tipo_formulario = isset($_POST['objetivo']) ? 'Reserva de Plaza' : 'Contacto';

// Construir el cuerpo del email
$cuerpo_email = "<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>";
$cuerpo_email .= "<h2 style='color: #56A9ED;'>Nuevo formulario: {$tipo_formulario}</h2>";
$cuerpo_email .= "<table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>";
$cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 200px;'>Nombre:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$nombre}</td></tr>";
$cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;'>Email:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$email}</td></tr>";

if (!empty($telefono_completo)) {
    $cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;'>Teléfono:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$telefono_completo}</td></tr>";
}

if (!empty($linkedin)) {
    $cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;'>LinkedIn:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'><a href='{$linkedin}'>{$linkedin}</a></td></tr>";
}

if (!empty($situacion_texto)) {
    $cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;'>Situación Actual:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$situacion_texto}</td></tr>";
}

if (!empty($objetivo)) {
    $cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; vertical-align: top;'>Objetivo Profesional:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$objetivo}</td></tr>";
}

if (!empty($mensaje)) {
    $cuerpo_email .= "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; vertical-align: top;'>Mensaje:</td><td style='padding: 10px; border-bottom: 1px solid #eee;'>{$mensaje}</td></tr>";
}

$cuerpo_email .= "</table>";
$cuerpo_email .= "<p style='margin-top: 20px; color: #666; font-size: 12px;'>Este email fue enviado desde el formulario de contacto del sitio web.</p>";
$cuerpo_email .= "</body></html>";

// Crear instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $smtp_port;
    $mail->CharSet = 'UTF-8';

    // Remitente
    $mail->setFrom($smtp_from_email, $smtp_from_name);
    
    // Destinatario
    $mail->addAddress($destinatario);

    // Contenido del email
    $mail->isHTML(true);
    $mail->Subject = "Nuevo formulario: {$tipo_formulario} - {$nombre}";
    $mail->Body = $cuerpo_email;
    $mail->AltBody = strip_tags($cuerpo_email);

    // Enviar email
    $mail->send();
    
    // Respuesta de éxito
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Formulario enviado correctamente. Te contactaremos pronto.'
    ]);
    
} catch (Exception $e) {
    // Error al enviar
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al enviar el formulario. Por favor, intenta nuevamente o contacta directamente.'
    ]);
    
    // Log del error (opcional, solo para debugging)
    error_log("Error PHPMailer: {$mail->ErrorInfo}");
}
?>

