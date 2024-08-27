<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Page</title>
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/cards.css">
        <script src="assets/font-awesome/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/font-awesome/css/all.css">
        <script src="assets/js/jquery-3.7.1.js"></script>
    </head>
    <body>
        <?php
            include('connect.php');

            // Fetch all categories
            $sql_categories = "SELECT * FROM categories";
            $result_categories = $conn->query($sql_categories);
        ?>

        <div id="container">
            <!-- The sidebar menu at the left-->
                <?php include('navbar.php') ?>
            <!-- End of sidebar menu at the left side-->

            <!-- The main content at the right-->
            <main id="main">
                <!-- The topbar -->
                <?php include('topbar.php') ?>
                <!-- End the of topbar -->

                <br>

                <!-- Sections for the content -->
                <div id="content">
                    <!-- the results of the election -->
                    <div class="cards-con">
                        <div>
                            <h1>20/24 ELECTION</h1>
                        </div>
                        <?php
                            if ($result_categories->num_rows > 0) {
                                while ($category = $result_categories->fetch_assoc()) {
                                    $category_id = $category['id'];
                                    $category_name = $category['category'];

                                    // Fetch candidates and their votes for the current category
                                    $sql_candidates = "SELECT candidates.name, candidates.image_path, COUNT(votes.id) as votes 
                                                    FROM candidates 
                                                    LEFT JOIN votes ON candidates.id = votes.candidate_id
                                                    WHERE candidates.category_id = $category_id
                                                    GROUP BY candidates.id";
                                    $result_candidates = $conn->query($sql_candidates);

                                    echo '<hr>';
                                    echo '<div><h2>' . $category_name . '</h2></div>';
                                    echo '<div class="cards">';

                                    if ($result_candidates->num_rows > 0) {
                                        while ($candidate = $result_candidates->fetch_assoc()) {
                                            echo '<div class="card">
                                                    <div id="image">
                                                         <img src="assets/uploads/' . $candidate['image_path'] . '" alt="' . $candidate['name'] . '">
                                                    </div>
                                                    <h4>' . $candidate['name'] . '</h4>
                                                    <div class="result">
                                                        <div>Votes</div>
                                                        <div id="result-num"><strong>' . $candidate['votes'] . '</strong></div>
                                                    </div>
                                                </div>';
                                        }
                                    } else {
                                        echo '<p>No candidates found for ' . $category_name . '.</p>';
                                    }

                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No categories found.</p>';
                            }
                        ?>
                    </div>
                </div>
                <!-- End of sections for the content -->
                 
                <!-- footer -->
                <?php include('footer.php')?>
                <!-- End of footer -->
            </main>
            <!-- End of the main content at the right-->
        </div>
    </body>
</html>
