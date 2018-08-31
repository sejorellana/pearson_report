<?php

include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/../Libs/FileLib.php';

/**
 * Provide all function related with the managment of files
 *
 * @author josuefrancisco
 */
class FileController {

    /**
     * __create
     * 
     * Create a file base on the inputs.
     * 
     * @param File $oFile
     * @param String $delimiter
     * @return Array result+message
     */
    private function __create($oFile, $delimiter) {
        $aReturn = array("result" => FALSE, "message" => ">The file wasn't created");
        try {
            $oFileLib = new FileLib();
            $aReturn = $oFileLib->create($oFile, $delimiter);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sMessage = "$sCode -- $sMsj -- $sLine";
            $aReturn = array("result" => FALSE, "message" => $sMessage);
        }
        return $aReturn;
    }

}

//$oFile = new File();
//$oFile->setName("prueba");
//$oFile->setExtension("txt");
//$lista = array(
//    array('aaa', 'bbb', 'ccc', 'dddd'),
//    array('123', '456', '789'),
//    array('"aaa"', '"bbb"')
//);
//$oFile->setContent($lista);
//$delimiter = " ";
//
//$oFileController = new FileController();
//echo json_encode($oFileController->test($oFile, $delimiter));
