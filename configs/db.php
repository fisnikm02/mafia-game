<?php

class DBInstance {
    private $conn;

    public function __construct($host, $username, $password, $database) {
        $this->conn = new mysqli($host, $username, $password, $database);
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }
}

$db = new DBInstance('localhost', 'root', '', 'buckaroo');