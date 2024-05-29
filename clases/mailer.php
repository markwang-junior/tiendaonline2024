<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer 
{

    function enviarEmail($emailDestinatario, $asunto, $cuerpo)
    {
        require_once __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Desactivar modo de depuración
            $mail->isSMTP(); // Usar SMTP
            $mail->Host = 'smtp.gmail.com'; // Dirección del servidor SMTP de Gmail
            $mail->SMTPAuth = true; // Habilitar autenticación SMTP
            $mail->Username = 'mark.wg2001@gmail.com'; // Su dirección de correo de Gmail
            $mail->Password = 'umajlzemeckvjelv'; // Contraseña de la aplicación generada
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar cifrado TLS
            $mail->Port = 587; // Puerto SMTP para Gmail

            // Destinatarios
            $mail->setFrom('mark.wg2001@gmail.com', 'WANG');
            $mail->addAddress($emailDestinatario); // Utiliza la variable $emailDestinatario

            // Contenido
            $mail->isHTML(true); // Establecer formato HTML
            $mail->Subject = $asunto;
            $mail->Body = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');

            // Establecer idioma del correo electrónico (opcional)
            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

            // Envío del correo electrónico
            if($mail->send()){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Manejar errores de manera adecuada, como registrarlos o notificar al administrador
            error_log("Error al enviar el correo electrónico: {$mail->ErrorInfo}");
            return false;
        }
    }
}

?>

 

