<?php
session_start();

include 'connect.php';

// Decode the JSON data received in the request
$data = json_decode(file_get_contents("php://input"));

if (isset($data->userId)) {
    // Sanitize and validate the candidate ID
    $candidateId = intval($data->userId);

    // First, retrieve the image path of the candidate
    $sql_get_image = "SELECT image_path FROM candidates WHERE id = $candidateId";
    $result = $conn->query($sql_get_image);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = 'assets/uploads/' . $row['image_path'];

        // Attempt to delete the file from the server
        if (file_exists($imagePath)) {
            if (!unlink($imagePath)) {
                echo json_encode(['success' => false, 'message' => "Error deleting image file."]);
                exit();
            }
        }

        // Prepare the SQL statement to delete the candidate from the database
        $sql = "DELETE FROM candidates WHERE id = $candidateId";

        // Execute the query and check for success
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Candidate deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => "Error: " . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Candidate not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid candidate ID.']);
}

$conn->close();
?>
