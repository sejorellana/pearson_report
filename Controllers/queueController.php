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
     * wfmAgentInterval
     * 
     * Get metric of the last half hour
     * 
     * @return Array
     */
    private function wfmQueueInterval() {
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
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/wfm/intervals/queue?start=$start&end=$end&limit=1000&includenulls=true");
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
     * interactions
     * 
     * Get the raw of interactions
     * 
     * @return Array
     */
    private function interactions() {
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
            $oApi->setUrl("https://api.cxengage.net/v1/tenants/$tenant/interactions?start=$start&end=$end&includenulls=true&limit=1000");
            $oApi->setData(array());

            $oCredential = new Credential();
            $oCredential->setUsername($username);
            $oCredential->setPassword($password);

            $oApiLib = new ApiLib();
            $aTmpInteractions = json_decode($oApiLib->get($oApi, $oCredential), true);
            if (array_key_exists("results", $aTmpInteractions)) {
                $aReturn = $aTmpInteractions["results"];
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

    private function getSegments() {
        $aReturn = array();
        try {
            $aSegments = array();
            $aInteractions = $this->interactions();
            foreach ($aInteractions as $interaction):
                if (array_key_exists("segments", $interaction)) {
                    $segments = $interaction["segments"];
                    foreach ($segments as $seg):
                        $interactionSegmentId = $seg["interactionSegmentId"];
                        if (!array_key_exists("$interactionSegmentId", $aSegments)) {
                            $aSegments["{$seg["interactionSegmentId"]}"] = $seg["segmentEndType"];
                        }
                    endforeach;
                }
            endforeach;
            $aReturn = $aSegments;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

    private function getSegmentsByQueue() {
        $aReturn = array();
        try {
            $aSegmentsByQueue = array();
            $aInteractions = $this->interactions();
            foreach ($aInteractions as $interaction):
                if (array_key_exists("queues", $interaction)) {
                    $segmentsByQueue = $interaction["queues"];
                    if (count($segmentsByQueue) > 0) {
                        foreach ($segmentsByQueue as $segByQueue):
                            $interactionSegmentId = $segByQueue["interactionSegmentId"];
                            $queue = $segByQueue["queueName"];
                            if (!array_key_exists("$interactionSegmentId", $aSegmentsByQueue)) {
                                $aSegmentsByQueue[$interactionSegmentId] = $queue;
                            }
                        endforeach;
                    }
                }
            endforeach;
            $aReturn = $aSegmentsByQueue;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return $aReturn;
    }

    private function getCount() {
        $aResult = array();
        try {
            $aSegments = $this->getSegments();
            $aSegmentsByQueue = $this->getSegmentsByQueue();
            $aQueues = array();
            foreach ($aSegmentsByQueue as $segmentQueueId => $queue):
                if (array_key_exists($queue, $aQueues)) {
                    $aQueues[$queue]["total"] += 1;
                    if ($aSegments["$segmentQueueId"] == "success") {
                        $aQueues[$queue]["asnwered"] += 1;
                    }
                } else {
                    if ($aSegments["$segmentQueueId"] == "success") {
                        $aQueues[$queue] = array("total" => 1, "asnwered" => 1);
                    } else {
                        $aQueues[$queue] = array("total" => 1, "asnwered" => 0);
                    }
                }
            endforeach;
            $aResult = $aQueues;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aResult;
    }

    /**
     * getQueuesReport
     * 
     * Get the report queuewise
     * 
     * @return Array
     */
    public function getQueuesReport() {
        $aReport = array();
        $aRawQueueInterval = $this->wfmQueueInterval();
        $aTmpInterval = $aRawQueueInterval["interval"];
        $aSegmentsCount = $this->getCount();
        if (array_key_exists("results", $aTmpInterval)) {
            $aInterval = $aTmpInterval["results"];
            foreach ($aInterval as $interval):
                $cHalfHour = $aRawQueueInterval["start"];
                $tcsData = "TCSDATA";
                $cCallType = $interval["queueName"];
                if (array_key_exists($cCallType, $aSegmentsCount)) {
                    $cSegments = $aSegmentsCount[$cCallType]["total"];
                    $cAnsweredSegments = $aSegmentsCount[$cCallType]["asnwered"];
                } else {
                    $cSegments = "0";
                    $cAnsweredSegments = "0";
                }

                $cAvgTimeToAnswer = $interval["avgTimeToAnswer"];

                $answerTime = $interval["answerTime"];
                $numberOfCalls = $interval["answeredCallCount"];
                if ($numberOfCalls != 0) {
                    $cAvgCallLength = $answerTime / $numberOfCalls;
                } else {
                    $cAvgCallLength = 0;
                }

                $agentWrapUpTime = $interval["agentWrapUpTime"];
                if ($numberOfCalls != 0) {
                    $cAvgWrapUpTime = $agentWrapUpTime / $numberOfCalls;
                } else {
                    $cAvgWrapUpTime = 0;
                }

                $cSla = $interval["sla"];
                array_push($aReport, array($cHalfHour, $tcsData, $cCallType, $cSegments, $cAnsweredSegments, $cAvgTimeToAnswer, $cAvgCallLength, $cAvgWrapUpTime, $cSla));
            endforeach;
        }
        return $aReport;
    }

}
