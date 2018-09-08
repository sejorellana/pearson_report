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
<<<<<<< HEAD
    private $folder;
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
=======
    private $folder;
>>>>>>> development

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
<<<<<<< HEAD
=======
>>>>>>> development
    function getFolder() {
        return $this->folder;
    }

<<<<<<< HEAD
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
=======
>>>>>>> development
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
<<<<<<< HEAD
=======
>>>>>>> development
    function setFolder($folder) {
        $this->folder = $folder;
    }

<<<<<<< HEAD
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
=======
>>>>>>> development
}
