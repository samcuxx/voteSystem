<?php
 session_start();
 
include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = trim($_POST['category']);

    // Check if the category already exists
    $stmt = $conn->prepare("SELECT * FROM categories WHERE category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Category already exists
        echo json_encode(['success' => false, 'message' => 'Category already exists.']);
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // Insert the new category
        $stmt = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
        $stmt->bind_param("s", $category);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'New category created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error adding category: " . $conn->error]);
        }
    }
    $stmt->close();
}

$conn->close();
?>
