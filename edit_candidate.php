<?php
 session_start();

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateId = $_POST['candidateIdEdit'];
    $category = $_POST['categoryEdit'];
    $candidateName = $_POST['candidateEdit'];
    $targetDir = "assets/uploads/";

    // Check if the candidate already exists in the same category (excluding the current candidate)
    $check_sql = "SELECT * FROM candidates WHERE name = ? AND category_id = ? AND id != ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("sii", $candidateName, $category, $candidateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Candidate already exists in the selected category.']);
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    $updateQuery = "UPDATE candidates SET name='$candidateName', category_id='$category'";

    if (!empty($_FILES["pictureEdit"]["name"])) {
        $fileName = basename($_FILES["pictureEdit"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["pictureEdit"]["tmp_name"], $targetFilePath)) {
                $updateQuery .= ", image_path='$fileName'";
            } else {
                echo json_encode(['success' => false, 'message' => 'File Upload Error: Sorry, there was an error uploading your file.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File Type Error: Only JPG, JPEG, PNG, & GIF files are allowed to upload.']);
            exit;
        }
    }

    $updateQuery .= " WHERE id='$candidateId'";

    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => "Database Error: " . $conn->error]);
    }
}

$conn->close();
?>
