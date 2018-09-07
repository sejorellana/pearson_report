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
<<<<<<< HEAD
    private $folder;
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975

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

<<<<<<< HEAD
    function getFolder() {
        return $this->folder;
    }

=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
    function setServer($server) {
        $this->server = $server;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

<<<<<<< HEAD
    function setFolder($folder) {
        $this->folder = $folder;
    }

=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
}
