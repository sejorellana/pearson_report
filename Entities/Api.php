<?php

/**
 * Define the body of the API call
 *
 * @author jorellana
 */
class Api {

    private $method = "GET";
    private $url;
    private $data = FALSE;

    function __construct() {
        
    }

    function getMethod() {
        return $this->method;
    }

    function getUrl() {
        return $this->url;
    }

    function getData() {
        return $this->data;
    }

    function setMethod($method) {
        $this->method = $method;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setData($data) {
        $this->data = $data;
    }

}
