<?php

include __DIR__ . '/../Controllers/ReportsController.php';

$oAgentReport = new ReportsController();
print_r($oAgentReport->getAgentPerformance());
