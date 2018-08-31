<?php

include_once __DIR__ . '/../Entities/File.php';

/**
 * Description of FileLIb
 *
 * @author josuefrancisco
 */
class FileLib {
    /**
     * create
     * 
     * Create a file base on csv structure. ALL files created are sent to the
     * default folder /Web/Uploads/Pendings
     * 
     * @param File $oFile
     * @param String $delimiter
     * @return Array result and message
     */
    public function create($oFile, $delimiter) {
        $aReturn = array("result" => FALSE, "message" => "default error");
        try {
            $list = $oFile->getContent();
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $dir = __DIR__ . "/../Web/Uploads/Pendings/";
            
            $fp = fopen($dir."$ifName.$ifExtension", 'w');

            foreach ($list as $row) {
                fputcsv($fp, $row, $delimiter);
            }
            $aReturn = array("result" => TRUE, "message" => "The file has been created successfully");
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }

}
