<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// start session
session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
  header("Location: home.php");
  die();
}

//database connection
include 'dbcon.php';

// WHEN USER CLICKS ON 'Create Account' BUTTON OF LOGIN FORM
if (isset($_POST['btnPostMe2'])) {

  //fetch input from signup form
  // the mysqli_real_escape_string() escapes special characters in a string for use in SQL statement.
  $username = trim(mysqli_real_escape_string($con, $_POST['username']));
  $email = trim(mysqli_real_escape_string($con, $_POST['email']));
  $password = trim(mysqli_real_escape_string($con, $_POST['password']));
  $cpassword = trim(mysqli_real_escape_string($con, $_POST['cpassword']));
  $code = mysqli_real_escape_string($con, md5(rand()));

  // check if same email exists already
  $emailquery = " SELECT * FROM registration WHERE email='$email' ";
  $query = mysqli_query($con, $emailquery);
  $emailcount = mysqli_num_rows($query);   // returns number of rows present at result set or checks if data is present in database.

  // conditions
  if ($emailcount > 0) {
?>
    <script>
      alert("This email already exists. Try using different email!")
    </script>
    <?php
  } else {
    // validate password in accordance to password policy set for user password
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if ($uppercase && $lowercase && $number && $specialChars && $password === $cpassword) {
      // user password encryption
      $pass = password_hash($password, PASSWORD_BCRYPT);
      $cpass = password_hash($cpassword, PASSWORD_BCRYPT);

      // insert query
      $insertquery = "INSERT INTO registration (username, email, password, cpassword,code) 
      VALUES ('$username', '$email', '$pass', '$cpass','$code')";
      $iquery = mysqli_query($con, $insertquery);
      // corresponding actions
      if ($iquery) {
        echo "<div style='display: none;'>";

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
          //Server settings
          $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
          $mail->isSMTP();                                            //Send using SMTP
          $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
          $mail->Username   = 'shaktisthaaa7@gmail.com';              //SMTP username
          $mail->Password   = 'ydhpvwyaghaxdknk';                     //SMTP password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
          $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

          //Recipients
          $mail->setFrom('shaktisthaaa7@gmail.com');
          $mail->addAddress($email);

          //Content
          $mail->isHTML(true);                                        //Set email format to HTML
          $mail->Subject = 'NO REPLY.';
          $mail->Body    = 'Here is your account verification link. Click on the link provided below to complete the signing up process of your account and then you are ready to login: <br/> <br/> <b><a href="http://localhost/LoginManagementSystem/?verification=' . $code . '">http://localhost/LoginManagementSystem/?verification=' . $code . '</a></b>';

          $mail->send();
          echo 'Message has been sent';
        } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo "</div>";
    ?>
        <script>
          alert("We've sent a verification link to your email address, please check to continue the signup process.");
          location.replace("index.php");
        </script>
      <?php
      } else {
      ?>
        <script>
          alert("Couldn't create your account. Try again!");
        </script>
      <?php
      }
    } elseif ($password !== $cpassword) {
      echo '<script>alert("Sorry! Passwords do not match.")</script>';
    } else {
      ?>
      <script>
        alert("Password requirements is not met. (8 characters in length, a number, an uppercase & lowercase letter each, a special character)");
      </script>
    <?php
    }
  }

  // Signup's reCAPTCHA
  if (isset($_POST['g-recaptcha-response'])) {
    $recaptcha = $_POST['g-recaptcha-response'];

    if (!$recaptcha) {
      echo '<script>alert("reCAPTCHA error! Please make sure to verify you are not a bot.")</script>';
    ?>
      <script>
        location.replace("index.php");
      </script>
      <?php
      exit;
    } else {
      $secret = "6Leybk8gAAAAAF6lgbQaPy_6brTFGtI7IAV2Usen";
      $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha;
      $response = file_get_contents(($url));

      $responseKeys = json_decode($response, true);

      // check if reCAPTCHA works well
      if ($responseKeys['success']) {
        // echo '<script>alert("You are verified as a human!")</script>';
      } else {
        echo '<script>alert("Something went wrong. Try again!")</script>';
      }
    }
  }
}

//email verification
include 'emailverification.php';

// WHEN USER CLICKS ON 'Login' BUTTON OF LOGIN FORM
if (isset($_POST['btnPostMe1'])) {

  // fetch input email and password from Login form
  $email1 = trim(mysqli_real_escape_string($con, $_POST["email1"]));
  $password1 = trim(mysqli_real_escape_string($con, $_POST["password1"]));

  // select query
  $email_search = " SELECT * FROM registration WHERE email='$email1' ";
  $query = mysqli_query($con, $email_search);
  $email_count = mysqli_num_rows($query);
  if ($email_count) {
    $email_pass = mysqli_fetch_assoc($query);

    // check if email is verified already
    if (empty($email_pass['code'])) {

      $db_pass = $email_pass['password'];
      $_SESSION['username'] = $email_pass['username'];

      // check if passwords matches each other
      $pass_decode = password_verify($password1, $db_pass);

      if ($pass_decode) {
        $_SESSION['SESSION_EMAIL'] = $email1;
      ?>
        <script>
          alert("Welcome <?php echo $_SESSION['username'] ?>!");
          location.replace("home.php");
        </script>
      <?php
      } else {
      ?>
        <script>
          alert("Incorrect password. Try again!");
          // location.replace("index.php");
        </script>
      <?php
      }
    } else {
      ?>
      <script>
        alert("Please verify your email first to login into your account.");
        location.replace("index.php");
      </script>
    <?php
    }
  } else {
    ?>
    <script>
      alert("Email doesn't exist or invalid email. Check again!");
    </script>
    <?php
  }

  // Login's reCAPTCHA
  if (isset($_POST['g-recaptcha-response'])) {
    $recaptcha = $_POST['g-recaptcha-response'];

    if (!$recaptcha) {
      echo '<script>alert("reCAPTCHA error! Please make sure to verify you are not a bot.")</script>';
    ?>
      <script>
        location.replace("index.php");
      </script>
<?php
      exit;
    } else {
      $secret = "6Leybk8gAAAAAF6lgbQaPy_6brTFGtI7IAV2Usen";
      $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha;
      $response = file_get_contents(($url));

      $responseKeys = json_decode($response, true);

      // check if reCAPTCHA works well
      if ($responseKeys['success']) {
        // echo '<script>alert("You are verified as a human!")</script>';
      } else {
        echo '<script>alert("Something went wrong with captcha. Try again!")</script>';
      }
    }
  }
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

  <!--  Iconscout CSS  -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

  <!--  CSS  -->
  <link rel="stylesheet" href="css/main.css">

  <!-- reCaptcha -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <title>Login System </title>
</head>


<body>
  <!-- HTML elements -->
  <div class="container">
    <div class="forms">
      <!-- LOGIN FORM -->
      <div class="form login">
        <span class="title">Login</span>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

          <div class="input-field">
            <input type="email" placeholder="Enter your vaild email" name="email1" required />
            <i class="uil uil-envelope icon"></i>
          </div>

          <div class="input-field">
            <input type="password" class="password" id="loginPassword" placeholder="Enter your password" name="password1" required />
            <i class="uil uil-lock icon"></i>

            <div class="pw-display-toggle-btn-loginpass">
              <i class="fa fa-eye"></i>
              <i class="fa fa-eye-slash"></i>
            </div>
          </div>

          <div class="checkbox-text">
            <a href="forgotpassword.php" class="text">Forgot password?</a>

            <div class="checkbox-content">
              <input type="checkbox" id="logCheck" />
              <label for="logCheck" class="text">Remember me</label>
            </div>
          </div>
          <div class="input-field button">
            <input type="submit" name="btnPostMe1" value="Login" />
          </div>
        </form>

        <div class="login-signup">
          <span class="text">Don't have an account yet?
            <a href="#" class="text signup-link">REGISTER</a>
          </span>
        </div>
      </div>

      <!-- REGISTRATION FORM -->
      <div class="form signup scroll">
        <span class="title1">Create an Account</span>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
          <div class="input-field">
            <input name="username" type="text" placeholder="Enter your fullname" id="name" minlength="4" value="<?php if (isset($_POST['btnPostMe2'])) {
                                                                                                              echo $username;
                                                                                                            } ?>" required />
            <i class="uil uil-user"></i>
          </div>

          <div class="input-field">
            <input type="email" placeholder="Enter your  vaild email" name="email" id="email" value="<?php if (isset($_POST['btnPostMe2'])) {
                                                                                                echo $email;
                                                                                              } ?>" required />
            <i class="uil uil-envelope icon"></i>
          </div>

          <div class="input-field">
            <input name="password" type="password" class="password" id="passField" placeholder="Create strong password" minlength="8" required />
            <i class="uil uil-lock icon"></i>

            <div class="pw-display-toggle-btn">
              <i class="fa fa-eye"></i>
              <i class="fa fa-eye-slash"></i>
            </div>
          </div>

          <!-- pass strength -->
          <div class="pw-strength">
            <span>Weak</span>
            <span></span>
          </div>

          <!-- Password policy -->
          <div id="password-policies" class="hide">
            <ul>
              <li class="invalid">Your password must contain :</li>
              <li id="length" class="invalid">
                <i class="fa fa-times" aria-hidden="true"></i> At least
                <strong>8 characters</strong> in length.
              </li>
              <li id="number" class="invalid">
                <i class="fa fa-times" aria-hidden="true"></i>
                A <strong>digit(0-9)</strong>.
              </li>
              <li id="upperCase" class="invalid">
                <i class="fa fa-times" aria-hidden="true"></i>
                An <strong>uppercase(A-Z)</strong> letter.
              </li>
              <li id="lowerCase" class="invalid">
                <i class="fa fa-times" aria-hidden="true"></i>
                A <strong>lowercase(a-z)</strong> letter.
              </li>
              <li id="specialCharacter" class="invalid">
                <i class="fa fa-times" aria-hidden="true"></i> A
                <strong>special character(!,*,$,#,@...)</strong>.
              </li>
            </ul>
          </div>

          <div class="input-field">
            <input name="cpassword" type="password" class="password" placeholder="Confirm your password" id="confPassword" required />
            <i class="uil uil-lock icon"></i>

            <div class="pw-display-toggle-btn-cpass">
              <i class="fa fa-eye"></i>
              <i class="fa fa-eye-slash"></i>
            </div>
          </div>

          <div class="checkbox-text" >
            <label style="color:green; font-size: 15px">I've read & agree every Privacy & Terms of use</label>
            <input  type="checkbox" id="logCheck" style="accent-color:green;" required />
          </div>

          <div class="checkbox-text">
            <div class="checkbox-content">
              <!-- reCaptcha -->
              <div class="g-recaptcha" data-sitekey="6Leybk8gAAAAADcz87WVI8XW5nI1VzOntNgZnxHZ"></div>
            </div>
          </div>

          <div class="input-field button">
            <input type="submit" name="btnPostMe2" value="Create Account" />
          </div>
        </form>

        <div class="login-signup reduce-margin">
          <span class="text">Already have an account?
            <a href="#" class="text login-link">LOGIN</a>
          </span>
        </div>

      </div>

    </div>
  </div>

  <script src="scripts/script.js">
    // javascript
  </script>
</body>

</html>