<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php'; // Make sure the path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail, Outlook, etc.
        $mail->SMTPAuth = true;
        $mail->Username = 'noor.saied.noor@gmail.com'; // Replace with your email
        $mail->Password = 'NizarAbbessiBoutellis'; // Use an app password if using Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Details
        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset';
        $mail->Body = 'Here’s your shiny new password: [new_password]';

        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo "Failed to send the email. Error: {$mail->ErrorInfo}";
    }
}
?>
