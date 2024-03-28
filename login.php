<?php
    session_start();

    include_once 'storage.php';
    $usersStorage = new Storage(new JsonIO('users.json'));
    $users = $usersStorage->findAll();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($username)) {
            $errors['username'] = 'Please enter your username.';
        }

        if (empty($password)) {
            $errors['password'] = 'Please enter your password.';
        }

        if (empty($errors)) {
            $authenticated = false;
            foreach ($users as $user) {
                if ($user['username'] === $username && $user['password'] === $password) {
                    $authenticated = true;

                    if ($username === 'admin') {
                        $_SESSION['admin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user['id'];
                    } else {
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user['id'];
                    }                    
                    header('Location: index.php');
                    exit;
                }
            }

            if (!$authenticated) {
                $errors['login'] = 'Invalid username or password. Please try again.';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>IIkémon</title>
</head>
<link rel="stylesheet" href="index.css">
    <body>
        <div class="loginContain">
            <div class="login">
                <h2>Login</h2>
                <form method="post" novalidate>
                    <div class="inputs">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <?php if (isset($errors['username'])) { ?>
                        <p style="color: red;"><?= $errors['username'] ?></p>
                    <?php } ?>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($errors['password'])) { ?>
                        <p style="color: red;"><?= $errors['password'] ?></p>
                    <?php } ?><br>
                    </div>

                    <input class="loginButton" type="submit" value="Login">
                </form>
            </div>
        </div>
        <?php
            if (isset($errors['login'])) {
                echo '<p style="color: red;">' . $errors['login'] . '</p>';
            }
        ?>
    </body>
</html>
