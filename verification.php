<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user data
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    // Generate unique verification code
    $verificationCode = strtoupper(bin2hex(random_bytes(3))); // e.g., "A1B2C3"

    // Save the code in session or database for validation during the verification step
    $_SESSION['verification_code'] = $verificationCode;
    $_SESSION['verification_email'] = $email;

    // Send verification email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Replace with your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; 
        $mail->Password   = 'your_password'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'Your Website');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification Code';
        $mail->Body    = "<h1>Account Verification</h1><p>Your verification code is: <strong>$verificationCode</strong></p><p>Please enter this code on the verification page to complete your sign-up.</p>";

        $mail->send();
        echo "Verification email sent successfully.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Send SMS (optional, using an example SMS API)
    $apiUrl = "https://api.smsprovider.com/send"; // Replace with your SMS API endpoint
    $apiKey = "your_api_key"; // Replace with your SMS API key

    $smsData = [
        'to' => $mobile,
        'message' => "Your verification code is: $verificationCode",
        'api_key' => $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($smsData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);

    // Check SMS response (based on your API's success criteria)
    if ($response) {
        echo "SMS sent successfully.";
    } else {
        echo "Failed to send SMS.";
    }

    // Redirect to the verification form
    header('Location: verification_form.php');
    exit;
}
?>
