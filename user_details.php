<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikémon</title>
</head>
    <body>

    <?php 
        if (!isset($_SESSION['admin'])) {
            echo '<div class="large">';
            echo '<a href="index.php">Back to Main Page</a>';
            echo '</div>';
        }
    ?>
    <link rel="stylesheet" href="index.css">
        <?php
            session_start();

            if (isset($_SESSION['admin'])) {
                header('Location: admin_details.php');
                exit;
            } else if (!isset($_SESSION['user_id'])) {
                echo 'Please <a href="login.php">login</a> to view this page.';
                exit;
            } else {
                include_once('storage.php');

                $userStorage = new Storage(new JsonIO('users.json'));
                $user = $userStorage->findById($_SESSION['user_id']);

                $pokemonsStorage = new Storage(new JsonIO('pokemons.json'));
                $pokemons = $pokemonsStorage->findAll();
                echo '<div class="index">';
                echo '<h1>Welcome, '. $user['username'] .'!  </h1>';
                echo '<p>Email: ' . $user['email'] . '</p>';
                echo '<p>Balance: $' . $user['balance'] . '</p>';

                if (!empty($user['cards'])) {
                    echo '<h2>Owned cards:</h2>';
                    echo '</div>';

                    echo '<div class="container">';

                    echo '<div class="card-container">';
                    foreach ($user['cards'] as $card_id) {
                        $card = $pokemons[$card_id];

                        echo '<div class="card ' . strtolower($card['type']) . '">';
                        echo '<h3>' . $card['name'] . '</h3>';
                        echo '<img src="' . $card['image'] . '" alt="' . $card['name'] . '">';
                        echo '<p>🏷️: ' . $card['type'] . '</p>';
                        //echo '<p>Description: ' . $card['description'] . '</p>';
                        echo '<p>❤️: ' . $card['hp'] . ' ⚔️: ' . $card['attack'] . ' 🛡️: ' . $card['defense'] . '</p>';


                        echo '<form method="post">';
                        echo '<input type="hidden" name="card_id" value="' . $card_id . '">';
                        echo '<input class="btnBuy" type="submit" name="sellCard" value="Sell for 90%">';
                        echo '</form>';

                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<p>No owned cards yet.</p>';
                }
            }

            if (isset($_POST['sellCard'])) {
                $card_id = $_POST['card_id'];

                if (array_key_exists($card_id, $pokemons)) {
                    $sellingPrice = $pokemons[$card_id]['price'] * 0.9;

                    $user['balance'] += $sellingPrice;
                    $user['ownedCards'] -= 1;

                    $user['cards'] = array_diff($user['cards'], [$card_id]);

                    $userStorage->update($_SESSION['user_id'], $user);
                    $pokemons[$card_id]['sold'] = false;
                    $pokemonsStorage->update($card_id, $pokemons[$card_id]);

                    header('Location: user_details.php');
                    exit();
                } else {
                    echo '<p>Error: Card not found.</p>';
                }
            }
        ?>
    </body>
</html>