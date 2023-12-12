<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "signup";

//connection to the database
$con = mysqli_connect($server, $user, $password, $db);

if (!$con) {
?>
    <script>
        alert("Sorry couldn't connect. Please check your network and try again!")
    </script>
<?php
}
// else {
//     <script>
//         alert("Connection successful!")
//     </script>

// }
?>