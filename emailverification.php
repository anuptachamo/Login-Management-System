<?php

// Database connection
include 'dbcon.php';

if (isset($_GET['verification'])) {
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM registration WHERE code='{$_GET['verification']}'")) > 0) {
        $query = mysqli_query($con, "UPDATE registration SET code='' WHERE code='{$_GET['verification']}'");
        // conditions
        if ($query) {
?>
            <script>
                alert("Your account has been verified ! Now you can login.");
            </script>
        <?php

        }
    } else {
        ?>
        <script>
            alert("Unable to verify your account!");
        </script>
<?php
        header("Location: index.php");
    }
}
?>