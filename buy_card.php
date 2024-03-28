<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['admin'])) {
            echo 'Admin user cannot buy cards.';
            exit;
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_POST['user_id'];
            $card_id = $_POST['card_id'];

            include_once('storage.php');

            $userStorage = new Storage(new JsonIO('users.json'));
            $users = $userStorage->findAll();
            $user = $userStorage->findById($user_id);

            $pokemonsStorage = new Storage(new JsonIO('pokemons.json'));
            $pokemons = $pokemonsStorage->findAll();
            $pokemon = $pokemonsStorage->findById($card_id);

            $cardCost = $pokemon['price'];
            $cardLimit = 5;

            if ($user = $users[$user_id]) {

                if ($user['balance'] >= $cardCost && $user['ownedCards'] < $cardLimit) {
                    $user['balance'] -= $cardCost;
                    $user['cards'][] = $card_id;
                    $user['ownedCards'] += 1;

                    $userStorage->update($user_id, $user);
                    $pokemon['sold'] = true;

                    $pokemonsStorage->update($card_id, $pokemon);

                    header('Location: user_details.php');
                    exit();
                }  else {
                echo 'Not enough balance or card limit reached.';
                }
            }  else {
                echo 'User not found.';
            }
        } else {
            echo 'User not logged in.';
        }
    } else {
        echo 'Invalid request method.';
    }
?>
