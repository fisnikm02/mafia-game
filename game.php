<?php
session_start();

// Include necessary files and classes
include_once 'configs/db.php';
include_once 'database/player.php';
include_once 'database/role.php';
include_once 'database/game.php';
include_once 'game_logic.php';

/// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Instantiate the game with the user's ID
$game = new MafiaGame($db);

// Check if the game has started
if ($game->getStatus() == 'waiting') {
    $game->startGame();
}

// Game logic and actions here
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'vote':
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mafia Game</title>
</head>

<body>
    <h1>Mafia Game</h1>

    <h2>Welcome, <?php echo $_SESSION['email']; ?>!</h2>

    <div>
        <h3>Game Status</h3>
        <!-- Display game status, e.g., Waiting, Night, Day, Ended -->
        <div id="game-status"><?php echo $game->getStatus(); ?></div>
    </div>

    <div>
        <h3>Your Role</h3>
        <!-- Display the player's role -->
        <?php echo $game->getPlayerRole($_SESSION['user_id']); ?>
    </div>

    <div>
        <h3>Actions</h3>
        <!-- Display player actions and game events -->
        <?php echo $game->displayActions(); ?>
    </div>

    <form action="game.php" method="post">
        <!-- Implement game actions form (voting, special actions, etc.) -->
        <input type="hidden" name="action" value="vote">
        <button type="submit">Vote</button>
        <!-- Add more buttons or input fields for other actions -->
    </form>

    <hr>

    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>


    <script>
        $(document).ready(function() {
            function updateGameStatus() {
                $.ajax({
                    url: 'get_game_status.php', // This PHP file returns the current game status
                    type: 'GET',
                    success: function(response) {
                        $('#game-status').html(response); // Update the game status on the page
                    }
                });
            }

            // Update game status every 5 seconds
            setInterval(updateGameStatus, 5000);
        });
    </script>
</body>

</html>