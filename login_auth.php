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

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $indexNumber = sanitize_input($conn, $_POST['indexNumber']);
    $password = sanitize_input($conn, $_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE indexNumber = ?");
    $stmt->bind_param("s", $indexNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User exists, fetch details
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password matches, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['indexNumber'] = $indexNumber;
            $_SESSION['user_id'] = $row['id']; // Save user ID in session
            $_SESSION['role'] = $row['role']; // Save user role in session

            // Check user role and redirect
            if ($row['role'] === 'admin') {
                header('Location: index.php');
            } else {
                // User is a voter, check if they have already voted
                $userId = $row['id'];
                $stmt_check_vote = $conn->prepare("SELECT * FROM votes WHERE user_id = ?");
                $stmt_check_vote->bind_param("i", $userId);
                $stmt_check_vote->execute();
                $result_check_vote = $stmt_check_vote->get_result();

                if ($result_check_vote->num_rows > 0) {
                    // User has already voted
                    header('Location: thank_you.php');
                } else {
                    // User has not voted yet
                    header('Location: vote.php');
                }
            }
            exit;
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid password.";
            header('Location: login.php');
            exit;
        }
    } else {
        // Invalid index number
        $_SESSION['error'] = "Invalid index number.";
        header('Location: login.php');
        exit;
    }

    // Close statement
    $stmt->close();
    if (isset($stmt_check_vote)) $stmt_check_vote->close();
}

$conn->close();
?>
