<?php
 session_start();
 
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $candidateName = $_POST['candidate'];
    $targetDir = "assets/uploads/";

    // Check if the candidate already exists in the same category
    $check_sql = "SELECT * FROM candidates WHERE name = ? AND category_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("si", $candidateName, $category);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Candidate already exists in the selected category.']);
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    if (!empty($_FILES["picture"]["name"])) {
        $fileName = basename($_FILES["picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)) {
                $sql = "INSERT INTO candidates (name, category_id, image_path) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sis", $candidateName, $category, $fileName);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => "Database Error: " . $conn->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'File Upload Error: Sorry, there was an error uploading your file.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File Type Error: Only JPG, JPEG, PNG, & GIF files are allowed to upload.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File Selection Error: Please select a file to upload.']);
    }
}

$conn->close();
?>
