<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f1f1f1;
        font-family: Helvetica, sans-serif;
        margin: 0;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
        padding: 20px;
        background-color: #688cac;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, .3);
        border-radius: 10px;
    }

    .login-container div {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-size: 25px;
        font-weight: bolder;
        text-shadow: 0px 0px 3px rgb(0, 0, 0, .3);
    }

    .login-container form {
        display: flex;
        flex-direction: column;
        font-size: 20px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 20px;
        transition: border 0.3s ease;
    }

    .login-container input[type="text"]:focus,
    .login-container input[type="password"]:focus {
        border-color: blue;
        outline: none;
    }

    .login-container button {
        padding: 10px;
        background-color: blue;
        color: #688cac;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-container button:hover {
        background-color: green;
    }

    .login-container .error-message {
        color: red;
        margin-top: 10px;
        text-align: center;
        font-size: 16px;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div>Login</div>
        <?php
        session_start();
        // Check if there's an error message to display
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']); // Clear error after displaying it
        }
        ?>
        <form action="login_auth.php" method="post">
            <input type="text" name="indexNumber" pattern="\d{12}" title="Please enter a 12-digit index number"
                placeholder="Index Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>