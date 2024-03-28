<?php
    session_start();

    include_once('storage.php');
    $cards = new Storage(new JsonIO('pokemons.json'));
    $allCards = $cards->findAll();
    $adminCards = array_filter($allCards, function($card) {
        return !$card['sold'];
    });

    $errors = [];
    $success = '';


    if (!isset($_SESSION['admin'])) {
        echo 'Please <a href="login.php">login</a> as an admin to view this page.';
        exit;
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createCard'])) {
            $newCard = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'hp' => $_POST['hp'],
                'attack' => $_POST['attack'],
                'defense' => $_POST['defense'],
                'image' => $_POST['image'],
                'price' => $_POST['price'],
                'sold' => false,
            ];

            if (empty($newCard['name'])) {
                $errors['name'] = 'Name is required';
            }

            if (empty($newCard['type'])) {
                $errors['type'] = 'Type is required';
            } elseif (!in_array($newCard['type'], ['fire', 'electric', 'water', 'grass', 'bug', 'normal', 'poison'])) {
                $errors['type'] = 'Invalid type';
            }

            if (empty($newCard['description'])){
                $errors['description'] = 'Description is required';
            } elseif (str_word_count($newCard['description']) < 10) {
                $errors['description'] = 'Description should have at least 10 words';
            }

            if (empty($newCard['hp'])) {
                $errors['hp'] = 'HP is required';
            } elseif(!filter_var($newCard['hp'], FILTER_VALIDATE_INT)){
                $errors['hp'] = 'HP should be integer';
            }
    
            if (empty($newCard['attack'])) {
                $errors['attack'] = 'Attack is required';
            } elseif(!filter_var($newCard['attack'], FILTER_VALIDATE_INT)){
                $errors['attack'] = 'Attack should be integer';
            }
    
            if (empty($newCard['defense'])) {
                $errors['defense'] = 'Defense is required';
            } elseif(!filter_var($newCard['defense'], FILTER_VALIDATE_INT)){
                $errors['defense'] = 'Defense should be integer';
            }
    
            if (empty($newCard['image'])) {
                $errors['image'] = 'Image link is required';
            } elseif (!filter_var($newCard['image'], FILTER_VALIDATE_URL)){
                $errors['image'] = 'Image should be a valid link';
            }
    
            if (empty($newCard['price'])) {
                $errors['price'] = 'Price is required';
            } elseif(!filter_var($newCard['price'], FILTER_VALIDATE_INT)){
                $errors['price'] = 'Price should be integer';
            }

            if (empty($errors)) {
                $cards->add($newCard);
                $success = 'New card created successfully!';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ikémon</title>
    </head>
    <link rel="stylesheet" href="index.css">
    <body>
        <div class="large">
            <a href="index.php">Back to Main Page</a>
        </div>

        <div class="index">
            <h1>Welcome, Admin!</h1>
            <h3>Admin's cards:</h3>
        </div>

        <?php
            if (isset($success)) {
                echo '<p>' . $success . '</p>';
            }
            echo '<div class="container">';

            echo '<div class="card-container">';
            if (!empty($adminCards)) {
                foreach ($adminCards as $card) {
                    echo '<div class="card ' . strtolower($card['type']) . '">';
                    echo '<h3>' . $card['name'] . '</h3>';
                    echo '<img src="' . $card['image'] . '" alt="' . $card['name'] . '">';
                    echo '<p>🏷️: ' . $card['type'] . '</p>';
                    //echo '<p>Description: ' . $card['description'] . '</p>';
                    echo '<p>❤️: ' . $card['hp'] . ' ⚔️: ' . $card['attack'] . ' 🛡️: ' . $card['defense'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No admin cards available.</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '<a href="index.php">Back to Main Page</a>';
        ?>

        <div class="loginContain">
            <div class="login">
                <h2>Create a New Card</h2>
                <form method="post" novalidate>
                    <div class="inputs">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        <?php if (isset($errors['name'])) { ?>
                            <p style="color: red;"><?php echo $errors['name']; ?></p>
                        <?php } ?>
                        <label for="type">Type:</label>
                        <input type="text" id="type" name="type" required>
                        <?php if (isset($errors['type'])) { ?>
                            <p style="color: red;"><?php echo $errors['type']; ?></p>
                        <?php } ?>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea>
                        <?php if (isset($errors['description'])) { ?>
                            <p style="color: red;"><?php echo $errors['description']; ?></p>
                        <?php } ?>
                        <label for="hp">HP:</label>
                        <input type="number" id="hp" name="hp" required>
                        <?php if (isset($errors['hp'])) { ?>
                            <p style="color: red;"><?php echo $errors['hp']; ?></p>
                        <?php } ?>
                        <label for="attack">Attack:</label>
                        <input type="number" id="attack" name="attack" required>
                        <?php if (isset($errors['attack'])) { ?>
                            <p style="color: red;"><?php echo $errors['attack']; ?></p>
                        <?php } ?>
                        <label for="defense">Defense:</label>
                        <input type="number" id="defense" name="defense" required>
                        <?php if (isset($errors['defense'])) { ?>
                            <p style="color: red;"><?php echo $errors['defense']; ?></p>
                        <?php } ?>
                        <label for="image">Image link:</label>
                        <input type="text" id="image" name="image" required>
                        <?php if (isset($errors['image'])) { ?>
                            <p style="color: red;"><?php echo $errors['image']; ?></p>
                        <?php } ?>
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" required><br>  
                        <?php if (isset($errors['price'])) { ?>
                            <p style="color: red;"><?php echo $errors['price']; ?></p>
                        <?php } ?>
                    </div>
                    <input class="loginButton" type="submit" name="createCard" value="Create Card">
                </form>
            </div>
        </div>
    </body>
</html>
