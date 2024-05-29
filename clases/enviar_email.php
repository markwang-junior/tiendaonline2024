<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../phpmailer/src/PHPMailer.php';
require_once '../phpmailer/src/SMTP.php';
require_once '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Desactivar modo de depuración
    $mail->isSMTP(); // Usar SMTP
    $mail->Host = 'smtp.gmail.com'; // Dirección del servidor SMTP de Gmail
    $mail->SMTPAuth = true; // Habilitar autenticación SMTP
    $mail->Username = 'mark.wg2001@gmail.com'; // Su dirección de correo de Gmail
    $mail->Password = 'umajlzemeckvjelv'; // Contraseña de la aplicación generada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar cifrado TLS
    $mail->Port = 587; // Puerto SMTP para Gmail

    // Destinatarios
    $mail->setFrom('mark.wg2001@gmail.com', 'WANG');
    $mail->addAddress('jialeguoo.ali@gmail.com', 'Joe User');

    // Contenido
    $mail->isHTML(true); // Establecer formato HTML
    $mail->Subject = 'Detalles de su compra';

    
    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>El ID de su compra es <b>' . $id_transaccion . '</b></p>';

    $mail->Body = $cuerpo;

    $mail->AltBody = 'Le enviamos los detalles de su compra';

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electrónico de la compra: {$mail->ErrorInfo}";
}

?>

