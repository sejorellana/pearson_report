<?php

include_once __DIR__ . '/agentController.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';
include_once __DIR__ . '/queueController.php';
include_once __DIR__ . '/MailController.php';

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

}
