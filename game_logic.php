<?php
class MafiaGame {
    private $db;
    private $players;
    private $roles;
    private $game;

    public function __construct($db) {
        $this->db = $db;
        $this->players = [];
        $this->roles = [];
        $this->game = new Game(1, 'waiting'); // Assuming the game ID is 1
    }

    public function getStatus() {
        return $this->game->getStatus();
    }

    public function getPlayerRole($id) {
        return $this->players[$id]->getRole();
    }

    public function displayActions() {
        $output = '';

        // Implement logic to display player actions or game events
        // Here's a simple example: display a message for each player
        foreach ($this->players as $player) {
            $output .= "Player {$player->getId()}: {$player->getRole()} - Action: [Your custom action]\n";
        }

        return $output;
    }

    public function startGame() {
        // Assign roles and start the game
        $this->assignRoles();
        $this->game->setStatus('night');
    }

    public function nightPhase() {
        // Implement night phase logic
        // For simplicity, let's assume Mafia eliminates a random player
        $victimId = $this->getRandomPlayerId();
        $this->eliminatePlayer($victimId);

        // Proceed to the day phase
        $this->game->setStatus('day');
    }

    public function dayPhase() {
        // Implement day phase logic
        // For simplicity, let's assume players vote to eliminate a random player
        $victimId = $this->getRandomPlayerId();
        $this->eliminatePlayer($victimId);

        // Check for the end of the game
        $this->checkGameEnd();
    }

    public function endGame() {
        // Determine game outcome
        // For simplicity, let's assume Mafia wins if they outnumber villagers
        $mafiaCount = $this->getRoleCount('Mafia');
        $villagerCount = $this->getRoleCount('Villager');

        if ($mafiaCount >= $villagerCount) {
            $this->game->setStatus('ended');
            echo "Game Over! Mafia Wins!";
        } else {
            $this->game->setStatus('ended');
            echo "Game Over! Villagers Win!";
        }
    }

    private function assignRoles() {
        // Assign roles to players
        $rolesQuery = $this->db->query("SELECT * FROM roles");
        

        if($rolesQuery) {
            $roles = $rolesQuery->fetch_all(MYSQLI_ASSOC);

            shuffle($roles);

            $playerCount = 10;
            for ($i = 0; $i < $playerCount; $i++) {
                $role_id = $i;
                if($i >= 4) {
                    $role_id = rand(0, 3);
                }
                $player = new Player($i + 1, "Player$i@gmail.com", $roles[$role_id]['id'], true);
                $this->players[] = $player;

                $id = $player->getId();
                $email = $player->getEmail();
                $role_id = $player->getRole();

                // Save player information to the database (you may need to adjust this)
                $this->db->query("INSERT INTO players (`email`, `role_id`, `is_alive`) VALUES ('$email', '$role_id', '1')");
            }
        } else {
            return;
        }
    }

    private function eliminatePlayer($playerId) {
        // Mark the player as eliminated
        foreach ($this->players as $player) {
            if ($player->getId() == $playerId) {
                $player->setAlive(false);

                // Update the database to reflect the player's elimination (you may need to adjust this)
                $this->db->query("UPDATE players SET alive = 0 WHERE id = $playerId");
                break;
            }
        }
    }

    private function checkGameEnd() {
        // Check if the game has reached a conclusion
        $mafiaCount = $this->getRoleCount('Mafia');
        $villagerCount = $this->getRoleCount('Villager');

        if ($mafiaCount == 0) {
            $this->endGame(); // Villagers win
        } elseif ($mafiaCount >= $villagerCount) {
            $this->endGame(); // Mafia win
        }
    }

    private function getRoleCount($roleName) {
        // Count the number of players with the specified role
        $count = 0;
        foreach ($this->players as $player) {
            if ($player->getRole() == $roleName && $player->isAlive()) {
                $count++;
            }
        }
        return $count;
    }

    private function getRandomPlayerId() {
        // Get the ID of a random alive player
        $alivePlayers = array_filter($this->players, function ($player) {
            return $player->isAlive();
        });

        $randomPlayer = array_values($alivePlayers)[array_rand($alivePlayers)];
        return $randomPlayer->getId();
    }
}
?>
