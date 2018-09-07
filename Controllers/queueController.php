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
            if ($aRange["result"]) {
//                $start = $aRange["start"];
//                $end = $aRange["end"];
                $start = "2018-09-06T00:00:00.000Z";
                $end = "2018-09-06T23:59:59.000Z";
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

    /**
     * Get the content of the interaction request
     * 
     * @return Array
     */
    private function getResult() {
        $aResult = array();
        try {
            $aTmpInteractions = $this->interaction();
            if (array_key_exists("results", $aTmpInteractions)) {
                if ($aTmpInteractions["results"] != NULL) {
                    $aResult = $aTmpInteractions["results"];
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aResult;
    }

    /**
     * getSegmentsMetrics
     * 
     * Get the total of segments and the total of answered segments
     * 
     * @param Array $aResults
     * @return Array
     */
//    private function getSegmentsMetrics($aResults) {
//        $aReturn = array();
//        try {
//            $iCountSegments = 0;
//            $iAnsweredSegments = 0;
//            foreach ($aResults as $interaction):
//                $aSegments = $interaction["segments"];
//                $iCountSegments += count($aSegments);
//                foreach ($aSegments as $segment):
//                    if ($segment["segmentEndType"] == "success") {
//                        $iAnsweredSegments++;
//                    }
//                endforeach;
//            endforeach;
//            array_push($aReturn, array("count" => $iCountSegments, "answered" => $iAnsweredSegments));
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
//        return $aReturn;
//    }

    public function getQueueWise() {
        $aReport = array();
        $aRange = $this->getRange();
        $aResults = $this->getResult();
        $aSegmentsMetrics = $this->getSegmentsMetrics($aResults);
        //columns
        $halfHours = $aRange["start"];
        $TcsData = "TCSDATA";
        $allSegments = $aSegmentsMetrics["count"];
        $answeredSegmets = $aSegmentsMetrics["answered"];

        array_push($aReport, array($halfHours, $TcsData, $allSegments, $answeredSegmets));
        return $aReport;
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
$o = new queueController();
echo json_encode($o->test());
