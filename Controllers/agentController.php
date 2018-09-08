<?php

include_once __DIR__ . '/TimeController.php';
include_once __DIR__ . '/../Entities/Api.php';
include_once __DIR__ . '/../Entities/Credential.php';
include_once __DIR__ . '/../Libs/ApiLib.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';
include_once __DIR__ . '/MailController.php';

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
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aJson;
    }

    /**
     * getRange
     * 
     * Get the range of time to analyze
     * 
     * @return Array
     */
    private function getRange() {
        try {
            $oTime = new TimeController();
            $aTimes = $oTime->getRange();
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aTimes;
    }

    /**
     * wfmAgentInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmAgentInterval() {
        $aReturn = array();
        try {
            $aSetup = $this->parseSetup();
            $tenant = $aSetup["tenantId"];
            $username = $aSetup["username"];
            $password = $aSetup["password"];

            $aRange = $this->getRange();
            if ($aRange["result"]) {
                $start = $aRange["start"];
                $end = $aRange["end"];
//                $start = "2018-09-06T12:00Z";
//                $end = "2018-09-06T12:30";
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
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aReturn;
    }

    /**
     * wfmAgentQueueInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmAgentQueueInterval() {
        $aReturn = array();
        try {
            $aSetup = $this->parseSetup();
            $tenant = $aSetup["tenantId"];
            $username = $aSetup["username"];
            $password = $aSetup["password"];

            $aRange = $this->getRange();
            if ($aRange["result"]) {
                $start = $aRange["start"];
                $end = $aRange["end"];
//                $start = "2018-09-06T12:00Z";
//                $end = "2018-09-06T12:30";
            }

            $oApi = new Api();
            $oApi->setMethod("GET");
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/wfm/intervals/agent-queue?start=$start&end=$end&limit=1000");
            $oApi->setData(array());

            $oCredential = new Credential();
            $oCredential->setUsername($username);
            $oCredential->setPassword($password);

            $oApiLib = new ApiLib();
            $aReturn = array("interval" => json_decode($oApiLib->get($oApi, $oCredential), true), "start" => $start);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
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
        try {
            $aRawAgentInterval = $this->wfmAgentInterval();
            $aTmpInterval = $aRawAgentInterval["interval"];
            $aRawAgentQueueInterval = $this->wfmAgentQueueInterval();
            $aTmpAgentQueueInterval = $aRawAgentQueueInterval["interval"];
            $cCounter = 0;
            if (array_key_exists("results", $aTmpInterval) && array_key_exists("results", $aTmpAgentQueueInterval)) {
                $aInterval = $aTmpInterval["results"];
                $aIntervalAgentQueue = $aTmpAgentQueueInterval["results"];
                foreach ($aInterval as $interval):
                    $cAgentID = $interval["agentId"];
                    $cCounter++;
                    $cAvgCallLength = $interval["avgHandleTime"];
                    $cAvgCallLengthvgTimeToAnswer = $interval["avgTimeToAnswer"];
                    foreach ($aIntervalAgentQueue as $agentQueue):
                        if ($agentQueue["agentId"] == $cAgentID) {
                            $answeredCallCount = $agentQueue["answeredCallCount"];
                            $agentWrapUpTime = $agentQueue["agentWrapUpTime"];
                            $cCampaign = $agentQueue["queueName"];
                        } else {
                            $answeredCallCount = 0;
                            $agentWrapUpTime = 0;
                            $cCampaign = 0;
                        }
                    endforeach;
                    if ($answeredCallCount == 0) {
                        $cAvgwraplen = 0;
                    } else {
                        $cAvgwraplen = $agentWrapUpTime / $answeredCallCount;
                    }
                    $cCalls = $answeredCallCount;
                    $cHalfHour = $aRawAgentInterval["start"];
                    $cOfferedCallCount = $interval["offeredCallCount"];
                    array_push($aReport, array($cAgentID, $cAvgCallLength, $cAvgCallLengthvgTimeToAnswer, $cAvgwraplen, $cCalls, $cCampaign, $cCounter, $cHalfHour, $cOfferedCallCount));
                endforeach;
            }
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aReport;
    }

}
<?php

include_once __DIR__ . '/TimeController.php';
include_once __DIR__ . '/../Entities/Api.php';
include_once __DIR__ . '/../Entities/Credential.php';
include_once __DIR__ . '/../Libs/ApiLib.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';
include_once __DIR__ . '/MailController.php';

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
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aJson;
    }

    /**
     * getRange
     * 
     * Get the range of time to analyze
     * 
     * @return Array
     */
    private function getRange() {
        try {
            $oTime = new TimeController();
            $aTimes = $oTime->getRange();
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aTimes;
    }

    /**
     * wfmAgentInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmAgentInterval() {
        $aReturn = array();
        try {
            $aSetup = $this->parseSetup();
            $tenant = $aSetup["tenantId"];
            $username = $aSetup["username"];
            $password = $aSetup["password"];

            $aRange = $this->getRange();
            if ($aRange["result"]) {
                $start = $aRange["start"];
                $end = $aRange["end"];
//                $start = "2018-09-06T12:00Z";
//                $end = "2018-09-06T12:30";
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
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aReturn;
    }

    /**
     * wfmAgentQueueInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmAgentQueueInterval() {
        $aReturn = array();
        try {
            $aSetup = $this->parseSetup();
            $tenant = $aSetup["tenantId"];
            $username = $aSetup["username"];
            $password = $aSetup["password"];

            $aRange = $this->getRange();
            if ($aRange["result"]) {
                $start = $aRange["start"];
                $end = $aRange["end"];
//                $start = "2018-09-06T12:00Z";
//                $end = "2018-09-06T12:30";
            }

            $oApi = new Api();
            $oApi->setMethod("GET");
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/wfm/intervals/agent-queue?start=$start&end=$end&limit=1000");
            $oApi->setData(array());

            $oCredential = new Credential();
            $oCredential->setUsername($username);
            $oCredential->setPassword($password);

            $oApiLib = new ApiLib();
            $aReturn = array("interval" => json_decode($oApiLib->get($oApi, $oCredential), true), "start" => $start);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
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
        try {
            $aRawAgentInterval = $this->wfmAgentInterval();
            $aTmpInterval = $aRawAgentInterval["interval"];
            $aRawAgentQueueInterval = $this->wfmAgentQueueInterval();
            $aTmpAgentQueueInterval = $aRawAgentQueueInterval["interval"];
            $cCounter = 0;
            if (array_key_exists("results", $aTmpInterval) && array_key_exists("results", $aTmpAgentQueueInterval)) {
                $aInterval = $aTmpInterval["results"];
                $aIntervalAgentQueue = $aTmpAgentQueueInterval["results"];
                foreach ($aInterval as $interval):
                    $cAgentID = $interval["agentId"];
                    $cCounter++;
                    $cAvgCallLength = $interval["avgHandleTime"];
                    $cAvgCallLengthvgTimeToAnswer = $interval["avgTimeToAnswer"];
                    foreach ($aIntervalAgentQueue as $agentQueue):
                        if ($agentQueue["agentId"] == $cAgentID) {
                            $answeredCallCount = $agentQueue["answeredCallCount"];
                            $agentWrapUpTime = $agentQueue["agentWrapUpTime"];
                            $cCampaign = $agentQueue["queueName"];
                        } else {
                            $answeredCallCount = 0;
                            $agentWrapUpTime = 0;
                            $cCampaign = 0;
                        }
                    endforeach;
                    if ($answeredCallCount == 0) {
                        $cAvgwraplen = 0;
                    } else {
                        $cAvgwraplen = $agentWrapUpTime / $answeredCallCount;
                    }
                    $cCalls = $answeredCallCount;
                    $cHalfHour = $aRawAgentInterval["start"];
                    $cOfferedCallCount = $interval["offeredCallCount"];
                    array_push($aReport, array($cAgentID, $cAvgCallLength, $cAvgCallLengthvgTimeToAnswer, $cAvgwraplen, $cCalls, $cCampaign, $cCounter, $cHalfHour, $cOfferedCallCount));
                endforeach;
            }
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aReport;
    }

}
