<!DOCTYPE html>
<html>
<head>
    <title>Ikémon</title>
</head>
    <link rel="stylesheet" href="index.css">
    <body>
        <?php
            session_start();

            include_once 'storage.php';
            $usersStorage = new Storage(new JsonIO('users.json'));
            $users = $usersStorage->findAll();

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $password2 = trim($_POST['confirm_password'] ?? '');

            $errors = [];

            if ($_POST) {
                if (empty($username)) {
                    $errors['username'] = 'The username is required.';
                }

                if (empty($email)) {
                    $errors['email'] = 'The email is required.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'The email is invalid.';
                } else {
                    foreach ($users as $user) {
                        if ($user['email'] == $email) {
                            $errors['email'] = 'The email is already in use.';
                            break;
                        }
                    }
                }

                if (empty($password)) {
                    $errors['password'] = 'The password is required.';
                }

                if (empty($password2)) {
                    $errors['confirm_password'] = 'Confirm the password!';
                } elseif ($password != $password2) {
                    $errors['confirm_password'] = 'The passwords do not match.';
                }

                if (empty($errors)) {
                    $initialBalance = 500;
                    $initialCards = [];
                    $initialOwnedCards = 0;

                    $usersStorage->add([
                        'id' => uniqid(),
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'isAdmin' => false,
                        'balance' => $initialBalance,
                        'cards' => $initialCards,
                        'ownedCards' => $initialOwnedCards,
                    ]);

                    header('Location: index.php');
                    exit;
                }
            }
        ?>
        <div class="loginContain">
            <div class="login">
            <h2>Register</h2>
            <form method="post">
                <div class="inputs">
            
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $username ?>" required>
                    <?php if (isset($errors['username'])) { ?>
                        <p style="color: red;"><?= $errors['username'] ?></p>
                    <?php } ?>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $email ?>" required>
                    <?php if (isset($errors['email'])) { ?>
                        <p style="color: red;"><?= $errors['email'] ?></p>
                    <?php } ?>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($errors['password'])) { ?>
                        <p style="color: red;"><?= $errors['password'] ?></p>
                    <?php } ?>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <?php if (isset($errors['confirm_password'])) { ?>
                        <p style="color: red;"><?= $errors['confirm_password'] ?></p>
                    <?php } ?><br>
                </div>

                <input class="loginButton" type="submit" value="Register">
            </form>

            <p>Already have an account? <a href="login.php">Login</a></p> 
            </div>
        </div>
    </body>
</html>
