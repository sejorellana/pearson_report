<?php

include_once __DIR__ . '/agentController.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';
include_once __DIR__ . '/queueController.php';

/**
 * Description of ReportsController
 *
 * @author Josue.Orellana
 */
class ReportsController {

    /**
     * getAgentPerformance
     * 
     * Transform the agent performance report to *.txt
     * 
     * @return type
     */
    public function getAgentPerformance() {
        $aReturn = array();
        try {
            $oAgentController = new agentController();
            $aReport = $oAgentController->getAgentReport();

            $oFile = new File();
            $timestamp = new \DateTime();
            $oFile->setName("agentsPerformance_{$timestamp->format("YmdHis")}");
            $oFile->setExtension("txt");
            $oFile->setContent($aReport);
            $delimiter = " ";
            $oFileController = new FileController();
            $aReturn = array("creation" => $oFileController->create($oFile, $delimiter), "oFile" => $oFile);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

    /**
     * getAgentPerformance
     * 
     * Transform the agent performance report to *.txt
     * 
     * @return type
     */
    public function getQueueWise() {
        $aReturn = array();
        try {
            $oQueueController = new queueController();
            $aReport = $oQueueController->getQueuesReport();

            $oFile = new File();
            $timestamp = new \DateTime();
            $oFile->setName("queuewise_{$timestamp->format("YmdHis")}");
            $oFile->setExtension("txt");
            $oFile->setContent($aReport);
            $delimiter = " ";
            $oFileController = new FileController();
            $aReturn = array("creation" => $oFileController->create($oFile, $delimiter), "oFile" => $oFile);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

}
