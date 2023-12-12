<?php

//database connection
include 'dbcon.php';

if (isset($_GET['reset'])) {
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM registration WHERE code='{$_GET['reset']}'")) > 0) {

        //when clicked on "DONE" button
        if (isset($_POST['submit'])) {
            // user input
            $password1 = trim(mysqli_real_escape_string($con, $_POST['newpassword']));
            $cpassword1 = trim(mysqli_real_escape_string($con, $_POST['cnewpassword']));

            if ($password1 === $cpassword1) {
                // user password encryption
                $pass = password_hash($password, PASSWORD_BCRYPT);
                $cpass = password_hash($cpassword, PASSWORD_BCRYPT);

                $query = mysqli_query($con, "UPDATE registration SET password='{$pass}', cpassword='{$cpass}', code='' WHERE code='{$_GET['reset']}'");
                // conditions
                if ($query) {
                    echo '<script>alert("Your password reset is successful!")</script>';
                    header("Location: index.php");
                }
            } else {
                echo '<script>alert("Passwords do not match.Try again!")</script>';
            }
        }
    } else {
?>
        <script>
            alert("Please check the reset link again. Something seems off!");
            location.replace("forgotpassword.php");
        </script>
<?php
    }
} else {
    header("Location: index.php");
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!--  Iconscout CSS  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/resetPassword.css">

    <title>Password Change</title>
</head>

<body>
    <div class="container" style="margin-top:180px; height:83vh;">
        <form action="" method="POST">
            <h2>Reset password</h2>

            <p style="text-align: center;">Enter the new password for your account and confirm to change your account password.</p>

            <div class="input-field">
                <input id="" name="newpassword" type="password" placeholder="New password" minlength="8" required />
            </div>

            <div class="input-field">
                <input id="" name="cnewpassword" type="password" placeholder="Confirm New password" required />
            </div>

            <div class="input-field button" style="margin-bottom: 1rem;">
                <input type="submit" name="submit" value="DONE" />
            </div>
        </form>
    </div>

    <script src="scripts/scriptforchangepass.js">
    </script>
</body>

</html>