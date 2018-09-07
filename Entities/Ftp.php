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
    private $folder;

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

    function getFolder() {
        return $this->folder;
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

    function setFolder($folder) {
        $this->folder = $folder;
    }

}
