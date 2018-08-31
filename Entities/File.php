<?php

/**
 * Description of File
 *
 * @author josuefrancisco
 */
class File {

    private $name;
    private $extension;
    private $content = array();

    function __construct() {
        
    }

    function getName() {
        return $this->name;
    }

    function getExtension() {
        return $this->extension;
    }

    function getContent() {
        return $this->content;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setExtension($extension) {
        $this->extension = $extension;
    }

    function setContent($content) {
        $this->content = $content;
    }

}
