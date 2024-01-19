<?php
class Player {
    private $id;
    private $email;
    private $role;
    private $is_alive;

    public function __construct($id, $email, $role, $alive) {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->is_alive = $alive;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function isAlive() {
        return $this->is_alive;
    }

    public function setAlive($alive) {
        $this->is_alive = $alive;
    }
}
?>