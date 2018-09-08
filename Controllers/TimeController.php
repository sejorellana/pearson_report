<?php

include_once __DIR__ . '/MailController.php';

/**
 * This class provide the basic functionalities of time for the reports
 *
 * @author Josue.Orellana
 */
class TimeController {

    /**
     * now
     * 
     * Get the UTC now time
     * 
     * @return String date formated Y-d-m H:i:s
     */
    private function now() {
        try {
            $dtNow = new DateTime();
            $sUtc = $dtNow->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i');
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $sUtc;
    }

    /**
     * start
     * 
     * Get the start time for reports, half our before now
     * 
     * @return String date formated Y-d-m H:i:s
     */
    private function start($sDateTime) {
        try {
            $time = strtotime($sDateTime);
            $time = $time - (30 * 60);
            $date = date("Y-m-d H:i", $time);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $date;
    }

    /**
     * format
     * 
     * Provide the format Y-m-dTH:i:sZ to a date/time string given
     * 
     * @param String $date
     * @return String
     */
    private function format($date) {
        try {
            $aDate = explode(" ", $date);
            $time = $aDate[1];
            $aTime = explode(":", $time);
            $hour = $aTime[0];
            if ($aTime[1] < 15) {
                $minute = "00";
            } else {
                if ($aTime[1] >= 15 && $aTime[1] < 30) {
                    $minute = "15";
                } else {
                    if ($aTime[1] >= 30 && $aTime[1] < 45) {
                        $minute = "30";
                    } else {
                        $minute = "45";
                    }
                }
            }
            $sDateTime = $aDate[0] . "T" . $hour . ":" . $minute . "Z";
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $sDateTime;
    }

    /**
     * middle
     * 
     * Get the next 15 minutes of the timestamp given
     * 
     * @param String $sDateTime
     * @return Date
     */
    private function middle($sDateTime) {
        try {
            $time = strtotime($sDateTime);
            $time = $time + (15 * 60);
            $date = date("Y-m-d H:i", $time);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $date;
    }

    /**
     * getRange
     * 
     * Get the start and end time for a report
     * 
     * @return Array result, start, end
     */
    public function getRange() {
        $aReturn = array("result" => FALSE);
        try {
            $dtEnd = $this->now();
            $dtStart = $this->start($dtEnd);
            $sEnd = $this->format($dtEnd);
            $sStart = $this->format($dtStart);
            $sMiddle = $this->middle($dtStart);
            $sMiddle = $this->format($sMiddle);
            $aReturn = array("result" => TRUE, "start" => $sStart, "middle" => $sMiddle, "end" => $sEnd);
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
