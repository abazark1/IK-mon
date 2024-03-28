<?php
    session_start();
    include_once('storage.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ikémon</title>
</head>
    <link rel="stylesheet" href="index.css">
    <body>
        <div class="main">
            <div class="index">
            <h1>Welcome to the Ikémon Card Shop!</h1>
            <p class="large">Buy new Ikémon card from our collection: </p>
            <div class="large">
                <?php
                
                    $loggedIn = isset($_SESSION['username']) ;
                                        
                    echo '<form method="get">';
                    echo '<label for="cardFilter">Filter by Type:</label>';
                    echo '<select id="cardFilter" name="type">';
                    echo '<option value="">All Types</option>';
                    $pokemonTypes = ['fire', 'electric', 'water', 'grass', 'bug', 'normal', 'poison'];
            
                    foreach ($pokemonTypes as $type) {
                        $selected = ($_GET['type'] ?? '') === $type ? 'selected' : '';
                        echo '<option value="' . $type . '" ' . $selected . '>' . ucfirst($type) . '</option>';
                    }
                    echo '</select>';
                    echo '<input type="submit" value="Filter">';
                    echo '</form>';
                    
                    if ($loggedIn || isset($_SESSION['admin'])) {
                        $userStorage = new Storage(new JsonIO('users.json'));
                        $user = $userStorage->findById($_SESSION['user_id']);

                        echo '<p>Username: ' . $user['username'] . '</p>';
                        if(!isset($_SESSION['admin'])){
                            echo '<p>Balance: $' . $user['balance'] . '</p>';
                        }
                        echo '<a href="logout.php">Logout</a>';
                        echo ' | ';
                        echo '<a href="user_details.php">User Details</a>';
                    } else {
                        echo '<a href="register.php">Register</a>';
                        echo ' | ';
                        echo '<a href="login.php">Login</a>';
                    }
                ?>
            </div>
        </div>

        <div class="container">
            <div class="card-container">
                <?php
                    $pokemonsStorage = new Storage(new JsonIO('pokemons.json'));
                    $pokemons = $pokemonsStorage->findAll();

                    $filterType = $_GET['type'] ?? '';
                    foreach ($pokemons as $card_id => $pokemon) {
                        if ($pokemon['sold'] == false && (!$filterType || in_array($pokemon['type'], $pokemonTypes) && $pokemon['type'] == $filterType)) {
                            echo '<div class="card ' . strtolower($pokemon['type']) . '">';
                            echo '<h2>' . $pokemon['name'] . '</h2>';
                            echo '<p>Type: ' . $pokemon['type'] . '</p>';
                            echo '<img src="' . $pokemon['image'] . '" alt="' . $pokemon['name'] . '" onclick="window.location=\'details.php?id=' . $card_id . '\';">';
                            
                            if (isset($_SESSION['user_id']) && !isset($_SESSION['admin'])) {
                                echo '<form method="post" action="buy_card.php">';
                                echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
                                echo '<input type="hidden" name="card_id" value="' . $card_id . '">';
                                echo '<button class="btnBuy" type="submit">💰'. $pokemon['price'].'</button>';
                                echo '</form>';
                            }
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
