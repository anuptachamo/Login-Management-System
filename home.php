<?php

//start session
session_start();

if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'dbcon.php';

$query = mysqli_query($con, "SELECT * FROM registration WHERE email = '{$_SESSION['SESSION_EMAIL']}'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Security Prototype System</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
    <!-- <link rel="stylesheet" href="css/homestyling.css" /> -->
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
   
    <nav class="navbar">
        <div class="container-fluid">
            <h1>ACS</h1>
            <div class="collapse">
                <ul id="my-div" style="margin-left: 1000px">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section >
        <h2 style="margin-top: 500px">Welcome to the Dashboard  <span><?php echo $_SESSION['username'] ?> </span>.</h2>
    </section>
    <script src="scripts/scriptforhome.js">
        
    </script>
</body>

</html>

