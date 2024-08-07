<?php



include 'partials/header.php'; // Include navigation bar
include 'partials/dbconnect.php'; // Include database connection


$id = isset($_GET['catid']) ? htmlspecialchars($_GET['catid']) : '';
$id_th = isset($_GET['threadid']) ? htmlspecialchars($_GET['threadid']) : '';


// Assuming $_GET['threadid'] is properly validated
$id = $_GET['threadid'];
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'fussionchat';

$conn = mysqli_connect($server, $username, $password, $database);

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $commented_by_username = $_SESSION['username'];
}


// Fetch category information
$sql_cat = "SELECT * FROM `categories` WHERE category_id = $id";
$result_cat = mysqli_query($conn, $sql_cat);

$row_cat = mysqli_fetch_assoc($result_cat);

$catname = htmlspecialchars($row_cat['category_name']);
$catdesc = htmlspecialchars($row_cat['category_description']);


// Handle form submission for posting comments
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_conn = htmlspecialchars($_POST['comment']);

    $sql_insert = "INSERT INTO `comments` (`comment_by`, `comment_content`, `thread_id`, `comment_time`) VALUES ('$commented_by_username', '$comment_conn', '$id', current_timestamp());";
    $result_insert = mysqli_query($conn, $sql_insert);
}

?>
<script>
    // Function to hide alert after 3 seconds
    setTimeout(function() {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            var alert = new bootstrap.Alert(successAlert);
            
            alert.close();
        }

        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            var alert = new bootstrap.Alert(errorAlert);
            alert.close();
        }
    }, 3000);

    // Function to store the scroll position before form submission
    function storeScrollPosition() {
        var scrollPosition = window.scrollY;
        document.getElementById('scrollPosition').value = scrollPosition;
    }

    // Function to restore the scroll position after page reload
    function restoreScrollPosition() {
        var scrollPosition = <?php echo isset($_GET['scroll']) ? $_GET['scroll'] : 0; ?>;
        window.scrollTo(0, scrollPosition);
    }
</script>


<body onload="restoreScrollPosition()">

    <div class="container my-3">
        <div class="container mt-5 grey-bg">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Enjoy your community chat and join differnet people.</h1>
                    <hr>
                    <p>
                        Use respectful language, stay on topic, and avoid personal attacks or hate speech. Respect
                        privacy, report violations, and foster positive engagement for a welcoming atmosphere.
                        <br><i>Login to Connect to make a chat.</i>
                        <br>
                        <a href="#chats" class="btn btn-primary my-4">Join Community</a>

                    </p>
                </div>
            </div>
        </div>
    </div>

    


    <!-- Community chats section -->
    <div class="container my-2 grey-bg p-4" id="chats">
        <h3 class="text my-4 mb-5">Community chats</h3>
        <hr>
        <?php
        $sql_comments = "SELECT * FROM `comments` WHERE thread_id = $id";
        $result_comments = mysqli_query($conn, $sql_comments);
        if (mysqli_num_rows($result_comments) > 0) {
            while ($row = mysqli_fetch_assoc($result_comments)) {
                $com_id = $row['comment_id'];
                $content = htmlspecialchars($row['comment_content']); // Sanitize output
                $time = $row['comment_time'];
                $user = htmlspecialchars($row['comment_by']); // Sanitize output
                $timestamp = strtotime($row['comment_time']);
                $formatted_time = date('jS F g:i a', $timestamp);
                echo '<div class="toast mt-4" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto">'. $user .'</strong>
                            <small>'. $formatted_time .'</small>
                        </div>
                        <div class="toast-body">
                            ' . $content . '
                        </div>
                    </div>';
            }
        } else {
            echo '<div class="alert alert-info" role="alert">
                    <h1>No Result Found</h1>
                    <hr>
                    <p>There are no comments yet. Be the first person to comment.</p>
                </div>';
        }
        ?>

<?php
 
 if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
     echo '<div class="container mt-5">
         <hr>
         <form action=" '. $_SERVER["REQUEST_URI"] . '" method="post" ">
             <input type="hidden" id="scrollPosition" name="scrollPosition" value="0">
             <div class="mb-3">
                 <h4>Post a comment</h4>
                 <br>
                 <div class="mb-3">
                     <label for="comment" class="form-label">Join the chat:</label>
                     <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                 </div>
                 <button type="submit" class="btn btn-primary">Post comment</button>
             </div>
         </form>
     </div>';
 }else{
     echo '<div class="container mt-5 blur-container">
         <hr>
         <h4>Welcome to FussionChats!</h4>
         <p>Please login or sign up to chat.</p>
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
    

        
    </div>

    <?php include 'partials/footer.php'; // Include footer ?>

    <!-- JavaScript to initialize Bootstrap components -->
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
