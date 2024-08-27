<?php
 session_start();

include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $indexNumber = $_POST['indexNumber'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    // Check if the candidate already exists in the same user
    $check_sql = "SELECT * FROM users WHERE indexNumber = ? AND password = ? AND role = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("sss", $indexNumber, $hashedPassword, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User already exists in the selected role.']);
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // Insert the new category
        $sql = "INSERT INTO users (indexNumber, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $indexNumber, $hashedPassword, $role);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'New user added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error adding category: " . $conn->error]);
        }
    }
}
?>