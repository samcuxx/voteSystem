<?php
 session_start();

include ('connect.php');

$id = $_POST['categoryIdDelete'];

$sql = "DELETE FROM categories WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Category delected successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => "Error deleting category: " . $conn->error]);
}

$conn->close();
?>