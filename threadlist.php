<?php
include 'partials/header.php'; // nav bar included 
include 'partials/dbconnect.php'; // connect to database 

// Assuming $_GET['catid'] is properly validated
$id = isset($_GET['catid']) ? htmlspecialchars($_GET['catid']) : '';
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $commented_by_username = $_SESSION['username'];
}

// Fetch category information
$sql_cat = "SELECT * FROM `categories` WHERE category_id='$id'";
$result_cat = mysqli_query($conn, $sql_cat);
$row_cat = mysqli_fetch_assoc($result_cat);
$catname = htmlspecialchars($row_cat['category_name']);
$catdesc = htmlspecialchars($row_cat['category_description']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $thread_title = htmlspecialchars($_POST['th_title']);
    $thread_desc = htmlspecialchars($_POST['th_desc']);


    $sql_insert = "INSERT INTO `threads` (`thread_title`, `thread_description`,'created_user', `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$thread_title', '$thread_desc', '$commented_by_username',$id', '0', current_timestamp())";
    $result_insert = mysqli_query($conn, $sql_insert);
    if ($result_insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your community chat has been created successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Unable to create your community chat. Please try again later.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}

$randomNumber = rand(1, 5);
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
            background-color: #f8f9fa;
            /* Light grey background */
        }
    </style>
</head>
<body>

<div class="container my-3">
    <div class="container mt-5 grey-bg">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Connect with Fellow <?php echo $catname ?> Enthusiasts</h1>
                <p class="col-md-8 fs-5"> <?php echo $catdesc ?> </p>
                <hr>
                <p>
                    Use respectful language, stay on topic, and avoid personal attacks or hate speech. Respect
                    privacy, report violations, and foster positive engagement for a welcoming atmosphere.
                    <br>Login to Connect with our <?php echo $catname ?> Community.
                </p>
                <a class="btn btn-primary btn-lg" href="#chats">Join Community</a>
            </div>
        </div>
    </div>
</div>

<!-- Form to create new community chat -->
<?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<div class="container mt-5">
    <hr>
    <form action="' . htmlspecialchars($_SERVER["REQUEST_URI"]) . '" method="post">
        <div class="mb-3">
            <h4>Make your own community:</h4>
            <br>
            <label for="th_title" class="form-label">
                <h6>Title for your chat community:</h6>
            </label>
            <input type="text" class="form-control" id="th_title" name="th_title" aria-describedby="emailHelp"
                placeholder="Title goes here..">
            <div id="emailHelp" class="form-text">Give an appropriate title for your community.</div>
        </div>
        <div class="mb-3">
            <label for="th_desc" class="form-label">
                <h6>Write an Attractive Description to Encourage Others to Join You:</h6>
            </label>
            <textarea class="form-control" id="th_desc" name="th_desc" rows="3" placeholder="Description..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Make community</button>
    </form>
    </div>';
} else {
    echo '<div class="container mt-5 blur-container">
        <hr>
        <h4>Welcome to FussionChats!</h4>
        <p>Please login or sign up to create your own community.</p>
        <button class="btn btn-outline-primary mx-2" type="button" data-bs-toggle="modal"
            data-bs-target="#loginModal">Login</button>
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
            data-bs-target="#signupModal">Signup</button>
    </div>';
}
?>
<!-- Add your CSS in the head section of your HTML -->
<style>
.blur-container {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Community chats section -->
<div class="container my-3 grey-bg p-4" id="chats">
    <h3 class="text my-4 mb-5">Join the Community</h3>
    <p>Click on title to open the community chat.</p>
    <hr>
    <?php
    $sql_threads = "SELECT * FROM `threads` WHERE thread_cat_id = $id";
    $result_threads = mysqli_query($conn, $sql_threads);
    if (mysqli_num_rows($result_threads) > 0) {
        while ($row = mysqli_fetch_assoc($result_threads)) {
            $title = htmlspecialchars($row['thread_title']);
            $desc = htmlspecialchars($row['thread_description']);
            $id_thread = htmlspecialchars($row['thread_id']);
            echo '<div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        <img src="images/img3.png" alt="account image" width="50px">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1"><a href="thread.php?threadid='. $id_thread .'&catid='. $id .'" class="text-dark text-decoration-none">'. $title .'</a><h5>
                        ' . $desc . '
                    </div>
                </div> <hr>';
        }
    } else {
        echo '<div class="alert alert-info" role="alert">
                <h1>No Result Found</h1>
                <hr>
                <p>There is no community chat made till now.<br> Be the first person to make the community chat.</p>
            </div>';
    }
    ?>
</div>

<?php include 'partials/footer.php'; // footer included ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: false
        })
    })
    toastList.forEach(toast => toast.show());
});
</script>

</body>
</html>
