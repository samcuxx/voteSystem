<?php
 session_start();
 
include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['categoryIdEdit'];
    $category = trim($_POST['categoryEdit']);

    // Check if the new category name already exists
    $stmt = $conn->prepare("SELECT * FROM categories WHERE category = ? AND id != ?");
    $stmt->bind_param("si", $category, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Category already exists
        echo json_encode(['success' => false, 'message' => 'Category already exists.']);
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // Update the category name
        $stmt = $conn->prepare("UPDATE categories SET category = ? WHERE id = ?");
        $stmt->bind_param("si", $category, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Category updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error updating category: " . $conn->error]);
        }
    }
    $stmt->close();
}

$conn->close();
?>
