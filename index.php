<?php
session_start();

// Include necessary files and classes
include_once 'configs/db.php';

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email'], $_POST['login_password'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Validate user credentials
    $user = $db->query("SELECT * FROM players WHERE email = '$email' AND is_alive = 1 LIMIT 1")->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Redirect to game.php after successful login
        header('Location: game.php');
        exit;
    } else {
        $loginError = "Invalid email or password.";
    }
}

// Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_email'], $_POST['register_password'])) {
    $email = $_POST['register_email'];
    $password = password_hash($_POST['register_password'], PASSWORD_DEFAULT);

    // Insert new user into the database
    $result = $db->query("INSERT INTO players (email, password) VALUES ('$email', '$password')");

    if ($result) {
        // Automatically log in the new user
        $user = $db->query("SELECT * FROM players WHERE email = '$email'");
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Redirect to game.php after successful registration
        header('Location: game.php');
        exit;
    } else {
        $registerError = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mafia Game - Login/Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="text-center mt-4">
        <h1>Mafia Game</h1>
    
        <?php if (isset($loginError)) : ?>
            <p style="color: red;"><?php echo $loginError; ?></p>
        <?php endif; ?>
    
        <?php if (isset($registerError)) : ?>
            <p style="color: red;"><?php echo $registerError; ?></p>
        <?php endif; ?>
    </div>

    <div class="row w-100">
        <div class="col-12 col-xl-6">
            <form action="" method="post" class="pt-0 p-5">
                <h2>Login</h2>
                <div class="form-group">
                    <label for="login_email">Email:</label>
                    <input type="text" class="form-control" name="login_email" required>
                </div>
                <br>
                <div class="from-group">
                    <label for="login_password">Password:</label>
                    <input type="password" class="form-control" name="login_password" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
        <div class="col-12 col-xl-6">
            <form action="" method="post" class="pt-0 p-5">
                <h2>Register</h2>
                <div class="form-group">
                    <label for="register_email">Email:</label>
                    <input type="text" class="form-control" name="register_email" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="register_password">Password:</label>
                    <input type="password" class="form-control" name="register_password" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</body>

</html>