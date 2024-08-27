<?php
session_start();
include('connect.php');

// Function to sanitize input
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        $_SESSION['error'] = "You must be logged in to vote.";
        header('Location: login.php');
        exit;
    }

    $userId = $_SESSION['user_id']; // Ensure this is correctly set in your session
    $votes = $_POST['vote']; // This is an array of votes from each category

    $errors = [];
    $successes = 0;

    foreach ($votes as $categoryId => $candidateId) {
        $candidateId = intval($candidateId);
        $categoryId = intval($categoryId);

        // Prevent double voting for the same category
        $sql_check_vote = "SELECT * FROM votes WHERE user_id = $userId AND category_id = $categoryId";
        $result_check_vote = $conn->query($sql_check_vote);

        if ($result_check_vote->num_rows > 0) {
            $errors[] = "You have already voted in the category ID: $categoryId.";
            continue;
        }

        // Insert the vote into the database
        $sql_vote = "INSERT INTO votes (user_id, candidate_id, category_id) VALUES ($userId, $candidateId, $categoryId)";
        if ($conn->query($sql_vote) === TRUE) {
            $successes++;
        } else {
            $errors[] = "There was an error submitting your vote for category ID: $categoryId.";
        }
    }

    // Provide feedback based on the voting process
    if (count($errors) > 0) {
        $_SESSION['error'] = implode('<br>', $errors);
    }

    if ($successes > 0) {
        $_SESSION['success'] = "Your vote(s) have been submitted successfully.";
    }

    header('Location: thank_you.php'); // Redirect to thank_you.php
    exit;
}
?>
