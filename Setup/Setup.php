<?php

/**
 * Description of Setup
 *
 * @author Josue.Orellana
 */
class Setup {

    //CxEngnage Credentials
    private $cxId = "";
    private $cxUsername = "";
    private $cxPassword = "";
    //Ftp Crendentials
    private $ftpUser = "";
    private $ftpPassword = "";
    private $ftpPort = "";
    private $ftpFolder = "";
    //Tenant
    private $tenant = "";
    private $tenId = "";

    function __construct() {
        
    }

    function getCxId() {
        return $this->cxId;
    }

    function getCxUsername() {
        return $this->cxUsername;
    }

    function getCxPassword() {
        return $this->cxPassword;
    }

    function getFtpUser() {
        return $this->ftpUser;
    }

    function getFtpPassword() {
        return $this->ftpPassword;
    }

    function getFtpPort() {
        return $this->ftpPort;
    }

    function getFtpFolder() {
        return $this->ftpFolder;
    }

    function getTenant() {
        return $this->tenant;
    }

    function getTenId() {
        return $this->tenId;
    }

}
