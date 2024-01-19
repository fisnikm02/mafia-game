<?php
class Player {
    public $id;
    public $name;
    public $email;
    public $role;
    public $is_alive;

    public function __construct($id, $name, $email, $role, $alive) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->is_alive = $alive;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
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