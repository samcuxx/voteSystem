<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['error'])) {
    echo "<div class='error-msg'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<div class='success-msg'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}

$indexNumber = $_SESSION['indexNumber'];

include('connect.php');

// Fetch all categories
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Page</title>
    <link rel="stylesheet" href="assets/css/vote.css">
    <link rel="stylesheet" href="assets/css/cards.css">
    <script src="assets/font-awesome/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/font-awesome/css/all.css">
    <script src="assets/js/jquery-3.7.1.js"></script>
    <script>
        function validateForm() {
            let categories = document.querySelectorAll('.category');
            let valid = true;

            categories.forEach(category => {
                let selected = category.querySelector('input[type="radio"]:checked');
                if (!selected) {
                    category.querySelector('.warning').style.display = 'block';
                    valid = false;
                } else {
                    category.querySelector('.warning').style.display = 'none';
                }
            });

            return valid;
        }
    </script>
</head>

<body>
    <div id="container">
        <main id="main">
            <?php include('topbar.php'); ?>
            <br>

            <div id="content">
                <div class="cards-con">
                    <div>
                        <h1>20/24 ELECTION</h1>
                    </div>
                    <form action="vote_manage.php" method="post" onsubmit="return validateForm()">
                        <?php
                        if ($result_categories && $result_categories->num_rows > 0) {
                            while ($category = $result_categories->fetch_assoc()) {
                                $category_id = $category['id'];
                                $category_name = $category['category'];

                                // Fetch candidates for the current category
                                $sql_candidates = "SELECT * FROM candidates WHERE category_id = $category_id";
                                $result_candidates = $conn->query($sql_candidates);

                                echo '<hr>';
                                echo '<div class="category">';
                                echo '<h2>' . htmlspecialchars($category_name) . '</h2>';
                                echo '<div class="cards">';

                                if ($result_candidates && $result_candidates->num_rows > 0) {
                                    while ($candidate = $result_candidates->fetch_assoc()) {
                                        echo '<div class="card">
                                                <div id="image">
                                                    <img src="assets/uploads/' . htmlspecialchars($candidate['image_path']) . '" alt="' . htmlspecialchars($candidate['name']) . '">
                                                </div>
                                                <h4>' . htmlspecialchars($candidate['name']) . '</h4>
                                                <div class="result">
                                                    <input type="radio" name="vote[' . $category_id . ']" value="' . $candidate['id'] . '" required>
                                                    <label for="vote[' . $category_id . ']">Vote</label>
                                                </div>
                                              </div>';
                                    }
                                } else {
                                    echo '<p>No candidates found for ' . htmlspecialchars($category_name) . '.</p>';
                                }

                                echo '</div>';
                                echo '<p class="warning" style="display:none; color: red;">Please select a candidate for this category.</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No categories found.</p>';
                        }
                        ?>
                        <button class="btn" type="submit">Submit All Votes</button>
                    </form>
                </div>
            </div>

            <!-- footer -->
            <?php include('footer.php')?>
            <!-- End of footer -->
        </main>
    </div>
</body>

</html>
