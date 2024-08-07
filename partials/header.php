<?php
session_start(); // Start the session

echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand mx-4" href="index.php">CommunityChat</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="about.php">About</a>
       
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="contact.php">Contact</a>
        </li>
      </ul>
      <div class="d-flex align-items-center justify-content-center flex-grow-1">';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo '<h5 class="text-info my-0 mx-2"><b>Welcome ' . htmlspecialchars($_SESSION['username']) . '</b></h5>';
}




// Search form
echo '<form class="d-flex ms-auto" method="get" action="search.php" role="search">
          <input class="form-control me-2" name="search" type="search" placeholder="Search Community" aria-label="Search">
          <button class="btn btn-primary mx-2" type="submit">Search</button>';
 




if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  echo '<a href="logout.php" class="btn btn-outline-primary me-2">Logout</a>';
} else {
  echo '<button class="btn btn-outline-primary mx-2" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>';
}

echo '</form>
      </div>
    </div>
  </div>
</nav>';

// Include modals
include 'partials/signupmodel.php'; // Ensure paths are correct and modals are implemented properly
include 'partials/loginmodel.php';

// Display signup success message
if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
  echo '<div id="signupSuccessAlert" class="alert alert-success alert-dismissible fade show my-0" role="alert">
          <strong>Success!</strong> You can now login.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

// Display login error message
if (isset($_GET['loginfalse']) && $_GET['loginfalse'] == "true") {
  echo '<div id="loginErrorAlert" class="alert alert-danger alert-dismissible fade show my-0" role="alert">
          <strong>Error!</strong> Username or password was wrong.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

// Display login success message
if (isset($_GET['login']) && $_GET['login'] == "true") {
  echo '<div id="loginSuccessAlert" class="alert alert-success alert-dismissible fade show my-0" role="alert">
          <strong>Login Successful!</strong> You can now participate in chats.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FussionChats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .grey-bg {
            background-color: #f8f9fa; /* Light grey background */
        }
    </style>
</head>

<body>
<!-- JavaScript to close alert after 5 seconds -->
<script>
setTimeout(function() {
    var signupSuccessAlert = document.getElementById('signupSuccessAlert');
    var loginErrorAlert = document.getElementById('loginErrorAlert');
    var loginSuccessAlert = document.getElementById('loginSuccessAlert');

    if (signupSuccessAlert) {
        signupSuccessAlert.remove(); // Remove the alert after 5 seconds
    }
    if (loginErrorAlert) {
        loginErrorAlert.remove(); // Remove the alert after 5 seconds
    }
    if (loginSuccessAlert) {
        loginSuccessAlert.remove(); // Remove the alert after 5 seconds
    }
}, 5000); // Adjust the time interval (in milliseconds) as needed
</script>
