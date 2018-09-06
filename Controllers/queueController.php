<?php

include_once __DIR__ . '/TimeController.php';
include_once __DIR__ . '/../Entities/Api.php';
include_once __DIR__ . '/../Entities/Credential.php';
include_once __DIR__ . '/../Libs/ApiLib.php';

/**
 * Description of queueController
 *
 * @author Josue.Orellana
 */
class queueController {

    /**
     * parseSetup
     * 
     * Parse the setup file into an array
     * 
     * @return type
     */
    private function parseSetup() {
        try {
            $json = file_get_contents(__DIR__ . "/../Setup/setup.json");
            $aJson = json_decode($json, TRUE);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aJson;
    }

    /**
     * interaction
     * 
     * Get the raw response of the interactions
     * 
     * @return Array
     */
    private function interaction() {
        $aReturn = array();
        try {
            $aSetup = $this->parseSetup();
            $tenant = $aSetup["tenantId"];
            $username = $aSetup["username"];
            $password = $aSetup["password"];

            $aRange = $this->getRange();
            if($aRange["result"]){
                $start = $aRange["start"];
                $end = $aRange["end"];
            }

            $oApi = new Api();
            $oApi->setMethod("GET");
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/interactions?start=$start&end=$end&limit=1000&includenulls=true");
            $oApi->setData(array());

            $oCredential = new Credential();
            $oCredential->setUsername($username);
            $oCredential->setPassword($password);

            $oApiLib = new ApiLib();
            $aReturn = json_decode($oApiLib->get($oApi, $oCredential), true);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

//    private function interactionsId() {
//        $aReturn = array();
//        try {
//            $aBody = $this->interaction();
//            if (array_key_exists("results", $aBody)) {
//                $aInteractions = $aBody["results"];
//                foreach ($aInteractions as $row):
//                    array_push($aReturn, $row["interactionId"]);
//                endforeach;
//            }
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
//        return $aReturn;
//    }

    public function test() {
        return $this->interactionsId();
    }

    private function getRange() {
        try {
            $oTime = new TimeController();
            $aTimes = $oTime->getRange();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aTimes;
    }

}

//https://api.cxengage.net/v1/tenants/a44ed48e-8312-47f0-9bfa-6e41a4da1082/interactions/73685210-b07c-11e8-8aa4-3047015466dd/realtime-statistics/resource-wrap-up-time
//$o = new queueController();
//var_dump($o->test());
