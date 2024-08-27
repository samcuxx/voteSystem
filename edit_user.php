<?php

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}


include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['userIdEdit'];
    $indexNumber = $_POST['indexNumberEdit'];
    $password = $_POST['passwordEdit'];
    $role = $_POST['roleEdit'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists (excluding the current user)
    $check_sql = "SELECT * FROM users WHERE indexNumber = ? AND password = ? AND role = ? AND id != ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("sssi", $indexNumber, $hashedPassword, $role, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User already exists in the selected role.']);
        $stmt->close();
        $conn->close();
        exit();
    }else {
            // Update the category name
            $sql = "UPDATE users SET indexNumber = ?, password = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $indexNumber, $hashedPassword, $role, $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => "Error updating user: " . $conn->error]);
            }
        }
        $stmt->close();
    }

$conn->close();
?>