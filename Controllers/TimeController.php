<?php

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
            $sUtc = $dtNow->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
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
            $date = date("Y-m-d H:i:s", $time);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
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
            $sDateTime = $aDate[0] . "T" . $aDate[1] . "Z";
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $sDateTime;
    }

    /**
     * __getRange
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
            $aReturn = array("result" => TRUE, "start" => $sStart, "end" => $sEnd);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

}
