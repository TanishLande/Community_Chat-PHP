<?php
include 'partials/dbconnect.php';
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'fussionchat';

$conn = mysqli_connect($server, $username, $password, $database);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_title = htmlspecialchars($_POST['cat_title']);
    $category_desc = htmlspecialchars($_POST['category_desc']);

    // Assuming you have sanitized the inputs and they are safe to use directly in the query
    $sql_insert_cat = "INSERT INTO `categories` (`category_name`, `category_description`, `created`) VALUES ('$category_title', '$category_desc', current_timestamp())";

    if ($conn->query($sql_insert_cat) === TRUE) {
        echo '<div class="alert my-0 alert-success alert-dismissible fade show" role="alert" >
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

?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function() {
                alert.classList.remove('show');
                alert.classList.add('fade');
                alert.addEventListener('transitionend', () => alert.remove());
            }, 3000); // Adjust the timeout duration as needed (3000ms = 3 seconds)
        }
    });
</script>

    <?php include 'partials/header.php'; // nav bar included ?>

    <!-- Carousel Start -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/image.png" class="d-block w-100" alt="Community" height="600px">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Community is like a group where people with similar interests chat. Let's join one.</h5>
                    <button type="button" class="btn btn-primary"><a href="#category_jo" class="text-light text-decoration-none">Join Community</a></button>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/coding.png" class="d-block w-100" alt="Coding" height="600px">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Categories are places where different fields are organized. Let's make one.</h5>
                    <button type="button" class="btn btn-primary"><a href="#cat_title" class="text-light text-decoration-none">Make Category</a></button>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/coding.png" class="d-block w-100" alt="Coding" height="600px">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Categories can be anything you want people to chat about and create communities. Let's make one.</h5>
                    <button type="button" class="btn btn-primary"><a href="#cat_title" class="text-light text-decoration-none">Make Category</a></button>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel End -->

    <div class="container my-3">
        <h2 class="text-center my-5">Dive into Your Interests</h2>

        <!-- Card Start -->
        <div class="row">
            <?php
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['category_id'];
                    $cat = htmlspecialchars($row['category_name']);
                    $desc = htmlspecialchars($row['category_description']);
                    echo '<div class="col-md-4" id="category_jo">
                            <div class="card mb-4">
                                <img src="https://source.unsplash.com/random?sig=' . rand() . '" class="card-img-top" alt="Community Image">
                                <div class="card-body">
                                    <h5 class="card-title">' . $cat . '</h5>
                                    <p class="card-text">' . substr($desc, 0, 90) . '...</p>
                                    <a href="threadlist.php?catid=' . $id . '" class="btn btn-primary">Join Community</a>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            ?>

            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<div class="container mt-5">
    <hr>
    <form action="index.php" method="post">
        <div class="mb-3">
            <h4>Make your own community:</h4>
            <br>
            <label for="cat_title" class="form-label">
                <h6>Title for your Category</h6>
            </label>
            <input type="text" class="form-control" id="cat_title" name="cat_title" placeholder="Title..">
        </div>
        <div class="mb-3">
            <label for="category_desc" class="form-label">
                <h6>Give a good description for your Category:</h6>
            </label>
            <textarea class="form-control" id="category_desc" name="category_desc" rows="3" placeholder="Description..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Make community</button>
    </form>
</div>
';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_title = $_POST['cat_title'];
    $category_desc = $_POST['category_desc'];
    
    // Validate category title (You can add validation as needed)
    if (empty($cat_title)) {
        echo "Category title is required!";
        // Handle error scenario as needed
    }
    
    // Validate category description
    if (empty($category_desc)) {
        echo "Category description is required!";
        // Handle error scenario as needed
    } else {
        // Minimum word count validation (at least 20 words)
        $word_count = str_word_count($category_desc);
        if ($word_count < 20) {
            echo "Description must be at least 20 words!";
            // Handle error scenario as needed
        } else {
            // Proceed with saving the category details
            // Example: save to database or perform other actions
            echo "Category created successfully!";
        }
    }
}
            } else {
                echo '<div class="container mt-5 blur-container">
                        <hr>
                        <h4>Welcome to Communitychat!</h4>
                        <p>Please login or sign up to make your Category.</p>
                        <button class="btn btn-outline-primary mx-2" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>
                      </div>';
            }
            ?>
        </div>
        <!-- Card End -->
    </div>

    <style>
        .blur-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <?php include 'partials/footer.php'; // Include footer ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
