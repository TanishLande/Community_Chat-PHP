<?php

$showerror = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'partials/dbconnect.php';

    $user_email = $_POST['signupmail'];
    $user_password = $_POST['signuppassword'];
    $user_cpassword = $_POST['signupcpassword'];
    $user_name = $_POST['signupname'];

    // Check if email already exists
    $existsql = "SELECT * FROM `users` WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $existsql);

    // Error handling for SQL query
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $showerror = "Email already in use";
    } else {
        if ($user_password == $user_cpassword) {
            $hash = password_hash($user_password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`user_name`, `user_email`, `user_pass`, `time`) VALUES ('$user_name', '$user_email', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);

            // Error handling for SQL query
            if (!$result) {
                die("Error: " . mysqli_error($conn));
            }

            if ($result) {
                header('Location: index.php?signupsuccess=true');
                exit();
            } else {
                $showerror = "Unable to register. Please try again.";
            }
        } else {
            $showerror = "Passwords do not match";
        }
    }
    header("Location: index.php?signupsucess=false&error=$showerror");
    exit();
}
?>
