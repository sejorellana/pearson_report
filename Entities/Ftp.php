<?php

/**
 * The FTP class provide the structure to have a successful connection to a FTP
 *
 * @author Josue.Orellana
 */
class Ftp {

    private $server;
    private $username;
    private $password;

    function __construct() {
        
    }

    function getServer() {
        return $this->server;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function setServer($server) {
        $this->server = $server;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

}
