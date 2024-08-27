<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}

// Destroy the session to log the user out after voting
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Voting</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
            font-family: 'Helvetica', sans-serif;
            margin: 0;
        }

        .thank-you-container {
            text-align: center;
            padding: 50px;
            background-color: #f8f9fa;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
        }

        .thank-you-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .thank-you-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
        }

        .thank-you-container button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .thank-you-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="thank-you-container">
        <h1>Thank You for Voting!</h1>
        <p>Your vote has been recorded. We appreciate your participation in the 2024 election.</p>
        <a href="login.php"><button>Return to Login</button></a>
    </div>
</body>

</html>
