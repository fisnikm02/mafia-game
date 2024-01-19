<?php
include_once 'configs/db.php';
include_once 'game_logic.php';

$game = new MafiaGame($db);
echo $game->getStatus();
?>