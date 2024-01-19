<?php

session_start();

define('SHOW_ERRORS', true);

if(SHOW_ERRORS) {
    include 'show_errors.php';
}


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