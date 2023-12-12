<?php

// start session
session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: home.php");
    die();
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//database connection
include 'dbcon.php';

//when clickd on "SEND" button
if (isset($_POST['submit'])) {
    //user input
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $code = mysqli_real_escape_string($con, md5(rand()));

    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM registration WHERE email='$email'")) > 0) {
        $query = mysqli_query($con, "UPDATE registration SET code='{$code}' WHERE email='{$email}'");

        //conditions
        if ($query) {
            echo "<div style='display: none;'>";
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'shaktisthaaa7@gmail.com';                     //SMTP username
                $mail->Password   = 'ydhpvwyaghaxdknk';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('shaktisthaaa7@gmail.com');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'NO REPLY.';
                $mail->Body    = 'Here is your password reset link. Click on the link provided below to reset your account password: <br/> <br/> <b><a href="http://localhost/LoginManagementSystem/changepassword.php?reset=' . $code . '">http://localhost/LoginManagementSystem/changepassword.php?reset=' . $code . '</a></b>';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";
?>
            <script>
                alert("We've sent a reset link to your email address, you must click on the link to reset your password.");
                // location.replace("index.php");
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            alert("<?php echo $email ?> could not be found. Try again!");
        </script>
<?php
    }
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<!--  Iconscout CSS  -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- CSS -->
<!-- <link rel="stylesheet" href="css/homestyling.css" /> -->
<link rel="stylesheet" href="css/forgotPassword.css">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>

<body>
    <div class="container" style="margin-top:180px; height:83vh;">
        <form action="" method="POST">
            <h2>Forgot Password</h2>
            <p style="text-align: center;">Provide your email address below, we'll send you a link to reset your password.</p>

            <div class="input-field">
                <input name="email" type="email" placeholder="Enter your valid email" required />
            </div>

            <div class="input-field button">
                <input type="submit" name="submit" value="SEND LINK" />
            </div>
        </form>
    </div>

    <script src="scripts/scriptforchangepass.js">
        // Javascript
    </script>
</body>

</html>