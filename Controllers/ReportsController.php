<?php

include_once __DIR__ . '/agentController.php';
include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/FileController.php';

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
            $aReturn = $oFileController->create($oFile, $delimiter);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

}

$oReport = new ReportsController();
echo json_encode($oReport->getAgentPerformance());
