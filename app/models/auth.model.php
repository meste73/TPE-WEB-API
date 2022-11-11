<?php

    class AuthModel{

        private $db;

        function __construct(){
            $this->db = $this->connect();
        }

        private function connect(){
            return new PDO('mysql:host=localhost;'.'dbname=tpe_db;charset=utf8','root', '');
        }

        function getUser($email){
            $query = $this->db->prepare('SELECT * FROM administrator WHERE email = ?');
            $query->execute([$email]);
            return $query->fetch(PDO::FETCH_OBJ);
        }
    }