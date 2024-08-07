<?php
$showerror = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include 'partials/dbconnect.php';
    
    // Sanitize user inputs
    $email = mysqli_real_escape_string($conn, $_POST['loginmail']);
    $pass = mysqli_real_escape_string($conn, $_POST['loginpassword']);

    // Query to fetch user data based on email
    $sql = "SELECT * FROM `users` WHERE `user_email` = '$email'";
    $result = mysqli_query($conn, $sql);
    
    // Check if query executed successfully
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $numrows = mysqli_num_rows($result);

    if ($numrows == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password using password_verify function
        if (password_verify($pass, $row['user_pass'])) {
            session_start();
            
            // Store user information in session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['usermail'] = $email; // Store user's email in session
            $_SESSION['username'] = $row['user_name']; // Example: Store user's name in session
            
            header("Location: index.php");
            exit();
        } else {
            // Password does not match
            $showerror = "Invalid Credentials";
        }
    } else {
        // No user found with the given email
        $showerror = "Invalid Credentials";
    }
    
    // Redirect with error message if login fails
    header("Location: index.php?loginerror=true");
    exit;
}
?>
