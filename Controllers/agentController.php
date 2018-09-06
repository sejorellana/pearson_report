<?php

include_once __DIR__ . '/TimeController.php';
include_once __DIR__ . '/../Entities/Api.php';
include_once __DIR__ . '/../Entities/Credential.php';
include_once __DIR__ . '/../Libs/ApiLib.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';

/**
 * Description of agentController
 *
 * @author Josue.Orellana
 */
class agentController {

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

    private function getRange() {
        try {
            $oTime = new TimeController();
            $aTimes = $oTime->getRange();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aTimes;
    }
    
    /**
     * wfmInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmInterval() {
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
                $start = "2018-09-06T12:00Z";
                $end = "2018-09-06T12:30";
            }

            $oApi = new Api();
            $oApi->setMethod("GET");
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/wfm/intervals/agent?start=$start&end=$end&limit=1000");
            $oApi->setData(array());

            $oCredential = new Credential();
            $oCredential->setUsername($username);
            $oCredential->setPassword($password);

            $oApiLib = new ApiLib();
            $aReturn = array("interval" => json_decode($oApiLib->get($oApi, $oCredential), true), "start" => $start);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }
    
    /**
     * getAgentReport
     * 
     * Get the report agent performance
     * 
     * @return Array
     */
    public function getAgentReport() {
        $aReport = array();
        $aRawInterval = $this->wfmInterval();
        $aTmpInterval = $aRawInterval["interval"];
        $cCounter = 0;
        if (array_key_exists("results", $aTmpInterval)) {
            $aInterval = $aTmpInterval["results"];
            foreach ($aInterval as $interval):
                $cAgentID = $interval["agentId"];
                $cCounter++;
                $cAvgCallLength = $interval["avgHandleTime"];
                $cAvgCallLengthvgTimeToAnswer = $interval["avgTimeToAnswer"];

                $answeredCallCount = $interval["answeredCallCount"]; //answeredCallCount
                $agentWrapUpTime = $interval["agentWrapUpTime"];
                if ($answeredCallCount == 0) {
                    $cAvgwraplen = 0;
                } else {
                    $cAvgwraplen = $agentWrapUpTime / $answeredCallCount;
                }
                $cCalls = $answeredCallCount;
                $cCampaign = "Campaign";
                $cHalfHour = $aRawInterval["start"];
                $cOfferedCallCount = $interval["offeredCallCount"];
                array_push($aReport, array($cAgentID, $cAvgCallLength, $cAvgCallLengthvgTimeToAnswer, $cAvgwraplen, $cCalls, $cCampaign, $cCounter, $cHalfHour, $cOfferedCallCount));
            endforeach;
        }        
        return $aReport;
    }

}
