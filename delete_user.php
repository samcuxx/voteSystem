<?php
 session_start();

include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['userId'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User delected successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => "Error deleting user: " . $conn->error]);
    }
}
$conn->close();
?>