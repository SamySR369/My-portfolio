<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer-6.10.0/src/Exception.php';
require __DIR__ . '/../PHPMailer-6.10.0/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-6.10.0/src/SMTP.php';

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    header('Content-Type: application/json'); // Return JSON response

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid email address.'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'samys786786@gmail.com'; // Your Gmail
        $mail->Password   = 'vfgn glzw qbgn vaoa';   // Your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);                   // Visitor info
        $mail->addAddress('samys786786@gmail.com');      // Your Gmail

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        $mail->send();

        echo json_encode([
            'status' => 'success',
            'message' => 'Email sent successfully.'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mailer Error: ' . $mail->ErrorInfo
        ]);
    }
} else {
    // Not a POST request
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
