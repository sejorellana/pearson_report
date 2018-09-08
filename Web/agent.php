<?php

include __DIR__ . '/../Controllers/ReportsController.php';
include_once __DIR__ . '/../Controllers/FileController.php';
include_once __DIR__ . '/../Entities/Ftp.php';

$oAgentReport = new ReportsController();
$oFileController = new FileController();
$aFile = $oAgentReport->getAgentPerformance();
$oFile = $aFile["oFile"];

$json = file_get_contents(__DIR__ . "/../Setup/setup.json");
$aJson = json_decode($json, TRUE);

$oFtp = new Ftp();
$oFtp->setServer($aJson["ftpHost"]);
$oFtp->setUsername($aJson["ftpUser"]);
$oFtp->setPassword($aJson["ftpPassword"]);
$oFtp->setFolder($aJson["ftpFolder"]);

print_r($oFileController->sendReport($oFile, " ", $oFtp, $oFile->getName()));

