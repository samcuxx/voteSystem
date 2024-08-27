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
    <script src="assets/font-awesome/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/font-awesome/css/all.css">
    <script src="assets/js/jquery-3.7.1.js"></script>
    <style>
    .votersNum {
        padding-top: 60px;
        display: flex;
        justify-content: center;
        font-weight: bold;

    }

    .votersIt {
        padding: 5px 15px;
    }

    .votersi {
        display: flex;
        justify-content: space-between;
        color: rgba(255, 255, 255, .5);
    }

    .votersCon {
        padding: 20px;
        width: 300px;
        height: 170px;
        border-radius: 24px;
        color: #688cac;
        border: 1px solid #688cac;
    }

    .votersCon h4 {

        font-size: 20px;
    }

    .voters-count {
        color: #7cff70;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        font-size: 50px;

    }

    .voters-count span {}

    hr {
        border: 0.3px solid rgba(0, 0, 0, .1);
    }

    span {
        color: #7cff70;
    }
    </style>
</head>

<body>

    <?php

            include("connect.php");

            // SQL query to count admins and voters
            $sql = "SELECT
                        COUNT(*) AS users_count,
                        COUNT(CASE WHEN role = 'admin' THEN 1 END) AS admins_count,
                        COUNT(CASE WHEN role = 'voter' THEN 1 END) AS voters_count
                    FROM users";

            $result = $conn->query($sql);


            // Query for votes by candidate and category
            $sql_votes = "SELECT c.category, can.name AS candidate, COUNT(v.id) AS votes
            FROM candidates can
            JOIN votes v ON can.id = v.candidate_id
            JOIN categories c ON can.category_id = c.id
            GROUP BY c.category, can.name
            ORDER BY c.category, votes DESC";

            $result_votes = $conn->query($sql_votes);

            // Separate SQL queries for other counts
            $totalCandidatesQuery = "SELECT COUNT(*) AS total_candidates FROM candidates";
            $totalCategoriesQuery = "SELECT COUNT(*) AS total_categories FROM categories";
            $totalVotedQuery = "SELECT COUNT(DISTINCT user_id) AS total_unique_voters FROM votes";

            $totalCandidatesResult = $conn->query($totalCandidatesQuery);
            $totalCategoriesResult = $conn->query($totalCategoriesQuery);
            $totalVotedResult = $conn->query($totalVotedQuery);

            if ($result && $totalCandidatesResult && $totalCategoriesResult && $totalVotedResult) {
                $row = $result->fetch_assoc();
                $users_count = $row['users_count'];
                $admins_count = $row['admins_count'];
                $voters_count = $row['voters_count'];

                $total_candidates = $totalCandidatesResult->fetch_assoc()['total_candidates'];
                $total_categories = $totalCategoriesResult->fetch_assoc()['total_categories'];
                $total_voted = $totalVotedResult->fetch_assoc()['total_unique_voters'];
            } else {
                echo "Error: " . $conn->error;
            }

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
                <!-- contian for number of people that can vote and voted -->
                <div class="votersNum">

                    <!-- contian for number of people that can vote -->
                    <div class="votersCon">
                        <div class="votersIt">
                            <h4>Voters</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-user-friends fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $voters_count;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- contian for number of people that can voted -->
                    <div class="votersCon" style="margin-left: 30px;">
                        <div class="votersIt">
                            <h4>Voted</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-thumbs-up fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $total_voted;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- contian for number of people that can voted -->
                    <div class="votersCon" style="margin-left: 30px;">
                        <div class="votersIt">
                            <h4>Admins</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-user-cog fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $admins_count;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- contian for number of people that can vote and voted -->
                <div class="votersNum" style="padding: 0px;">

                    <!-- contian for number of people that can vote -->
                    <div class="votersCon">
                        <div class="votersIt">
                            <h4>Total Users</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-users fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $users_count;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- contian for number of people that can voted -->
                    <div class="votersCon" style="margin-left: 30px;">
                        <div class="votersIt">
                            <h4>Total Candidates</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-user-tie fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $total_candidates;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- contian for number of people that can voted -->
                    <div class="votersCon" style="margin-left: 30px;">
                        <div class="votersIt">
                            <h4>Total Categories</h4>
                            <br>
                            <hr>
                            <br>
                            <div class="votersi">
                                <span class="fa fa-list fa-2x"></span>
                                <h3 class="voters-count">
                                    <?php
                                            echo  $total_categories;
                                        ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <!-- End of sections for the content -->
            <div class="can-main">
                <!-- Candidate vote bars -->
                <div id="candidate-bars">
                    <?php
                    $current_category = '';
                    if ($result_votes) {
                        while ($row_votes = $result_votes->fetch_assoc()) {
                            if ($current_category != $row_votes['category']) {
                                if ($current_category != '') echo '</div>';
                                echo "<div class='category-title'>" . htmlspecialchars($row_votes['category']) . "</div>";
                                echo "<div class='candidate-bar'>";
                                $current_category = $row_votes['category'];
                            }
                            $total_votes = $row_votes['votes'];
                            $percentage = $total_voted > 0 ? ($total_votes / $total_voted) * 100 : 0; // Calculate percentage of votes
                            $bar_width = $percentage; // Set bar width based on percentage

                            echo "<div class='progress-container'>
                                    <div class='progress-bar' style='width: " . number_format($bar_width, 2) . "%;'>
                                        " . htmlspecialchars($row_votes['candidate']) . " - " . $total_votes . " votes (" . number_format($percentage, 2) . "%)
                                    </div>
                                </div>";
                        }
                        echo '</div>'; // Close last category div
                    } else {
                        echo "Error: " . $conn->error;
                    }
                    ?>
                </div>
            </div>
            <!-- End of candidate vote bars -->

            <!-- footer -->
            <?php include('footer.php')?>
            <!-- End of footer -->
        </main>
        <!-- End of the main content at the right-->
    </div>
</body>

</html>