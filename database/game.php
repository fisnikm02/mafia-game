<?php
class Game {
    private $id;
    private $status;

    public function __construct($id, $status) {
        $this->id = $id;
        $this->status = $status;
    }

    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>