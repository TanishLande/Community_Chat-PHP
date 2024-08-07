<?php
include 'partials/dbconnect.php';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $category_title = htmlspecialchars($_POST['cat_title']);
//     $category_desc = htmlspecialchars($_POST['category_desc']);

//     $sql_insert_cat = "INSERT INTO `categories` (`category_name`, `category_description`, `created`) VALUES (?, ?, current_timestamp())";
//     $stmt = $conn->prepare($sql_insert_cat);
//     $stmt->bind_param("ss", $category_title, $category_desc);

//     if ($stmt->execute()) {
//         echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
//                 <strong>Success!</strong> Your community chat has been created successfully.
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//               </div>';
//     } else {
//         echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
//                 <strong>Error!</strong> Unable to create your community chat. Please try again later.
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//               </div>';
//     }
//     $stmt->close();
// }
?>

<?php include 'partials/header.php'; // nav bar included ?>

<style>
    .main-searchresult {
        height: 100vh;
    }
</style>

<div class="container main-searchresult my-3">
    <h1>Search result for "<em><?php echo htmlspecialchars($_GET['search']); ?></em>"</h1>
    <div class="result my-2">

     <!--type of divider  -->
    <br class="my-4">

        <?php
        $search = htmlspecialchars($_GET['search']);
        $sql_2 = "SELECT * FROM `threads` WHERE MATCH(thread_title, thread_description) AGAINST (?)";
        $stmt_2 = $conn->prepare($sql_2);
        $stmt_2->bind_param("s", $search);
        $stmt_2->execute();
        $result_2 = $stmt_2->get_result();

        if ($result_2->num_rows > 0) {
            while($row = mysqli_fetch_assoc($result_2)){
                $title = htmlspecialchars($row['thread_title']);
                $desc = htmlspecialchars($row['thread_description']);
                $id_thread = htmlspecialchars($row['thread_id']);
                echo '<div class="d-flex align-items-center mb-4 my-2">
                    <div class="flex-shrink-0">
                        <img src="images/img3.png" alt="account image" width="50px">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1"><a href="thread.php?threadid='. $id_thread .'" class="text-dark text-decoration-none">'. $title .'</a></h5>
                        ' . $desc . '
                    </div>
                </div>
                <hr>';
            }
        } else {
            echo '<div class="alert alert-secondary my-2" role="alert">
                    <h1>No results found</h1>
                    <hr>
                    <p>Try a different search term.</p>
                </div>';
        }

        $stmt_2->close();
        ?>
    </div>
</div>

<?php include 'partials/footer.php'; // Include footer ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
