<?php
    session_start();
    include_once('storage.php');
    $pokemonsStorage = new Storage(new JsonIO('pokemons.json'));
    $pokemons = $pokemonsStorage->findAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ikémon</title>
    </head>
    <link rel="stylesheet" href="index.css">

    <body>
        <div class="large">
            <a href="index.php">Back to Main Page</a>
        </div>
        <div>
            <?php
                if (isset($_GET['id']) && array_key_exists($_GET['id'], $pokemons)) {
                    $card_id = $_GET['id'];
                    $pokemon = $pokemons[$card_id];

                    echo '<div class="large">';
                    echo '<h1>' . $pokemon['name'] . ' Details</h1>';
                    echo '<p>Description: ' . $pokemon['description'] . '</p>';
                    echo '</div>';

                    echo '<div class="details">';   
                    echo '<div class="card2 ' . strtolower($pokemon['type']) . '">';
                    echo '<div class="card-image">';
                    echo '<img src="' . $pokemon['image'] . '" alt="' . $pokemon['name'] . '">';
                    echo '</div>';
                    echo '<div class="card-details">';
                    echo '<h1>' . $pokemon['name'] . ' </h1>';
                    echo '<p style="font-size: x-large;"">🏷️ ' . $pokemon['type'] . '</p>';
                    echo '<p class="atrib">❤️ ' . $pokemon['hp'] . '</p>';
                    echo '<p class="atrib">⚔️ ' . $pokemon['attack'] . '</p>';
                    echo '<p class="atrib">🛡️ ' . $pokemon['defense'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo 'Card not found';
                }
            ?>
        </div>
    </body>
</html>
