<?php

include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/../Libs/FileLib.php';
include_once __DIR__ . '/../Entities/Ftp.php';
<<<<<<< HEAD
include_once __DIR__ . '/MailController.php';
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975

/**
 * Provide all function related with the management of files
 *
 * @author josuefrancisco
 */
class FileController {

    /**
<<<<<<< HEAD
     * create
=======
     * __create
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
     * 
     * Create a file base on the inputs.
     * 
     * @param File $oFile
     * @param String $delimiter
     * @return Array result+message
     */
<<<<<<< HEAD
    public function create($oFile, $delimiter) {
        $aReturn = array("result" => FALSE, "message" => "The file wasn't created");
=======
    private function __create($oFile, $delimiter) {
        $aReturn = array("result" => FALSE, "message" => ">The file wasn't created");
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        try {
            $oFileLib = new FileLib();
            $aReturn = $oFileLib->create($oFile, $delimiter);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
<<<<<<< HEAD
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
=======
            $sMessage = "$sCode -- $sMsj -- $sLine";
            $aReturn = array("result" => FALSE, "message" => $sMessage);
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        }
        return $aReturn;
    }

    /**
<<<<<<< HEAD
     * bkFile
=======
     * __bkFile
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
     * 
     * Make a backup for the file provided
     * 
     * @param File $oFile
     * @return Array result+message
     */
<<<<<<< HEAD
    private function bkFile($oFile) {
        $aReturn = array("result" => FALSE, "message" => "The file wasn't created");
=======
    private function __bkFile($oFile) {
        $aReturn = array("result" => FALSE, "message" => ">The file wasn't created");
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        try {
            //Input File/Directory
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $ifFile = __DIR__ . "/../Web/Pendings/$ifName.$ifExtension";
            //Output file/directory
            $ofFile = __DIR__ . "/../Web/Uploads/";
            $dtTimeStamp = new DateTime();
            $sTimestamp = $dtTimeStamp->format("dmYHis");
            $ofLink = "$ofFile/Reporte_$sTimestamp.txt";

            if (file_exists($ifFile)) {
                copy($ifFile, $ofLink) or die("Could not move the file");
                $aReturn = array("result" => TRUE, "message" => "The file was moved");
            } else {
                $aReturn = array("result" => FALSE, "message" => "The file $ifName.$ifExtension doesn't exist or is inaccessible");
            }
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
<<<<<<< HEAD
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
=======
            $sMessage = "$sCode -- $sMsj -- $sLine";
            $aReturn = array("result" => FALSE, "message" => $sMessage);
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        }
        return $aReturn;
    }

    /**
<<<<<<< HEAD
     * clean
=======
     * __clean
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
     * 
     * Delete a file
     * 
     * @param File $oFile
     */
<<<<<<< HEAD
    private function clean($oFile) {
=======
    private function __clean($oFile) {
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        try {
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $ifFile = __DIR__ . "/../Web/Pendings/$ifName.$ifExtension";
            unlink($ifFile);
        } catch (Exception $exc) {
<<<<<<< HEAD
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
=======
            echo $exc->getTraceAsString();
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        }
    }

    /**
<<<<<<< HEAD
     * cleanNewest
=======
     * __cleanNewest
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
     * 
     * Delete the newest file in the folder Uploads
     * 
     */
<<<<<<< HEAD
    private function cleanNewest() {
=======
    private function __cleanNewest() {
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        try {
            $ofFiles = scandir(__DIR__ . "/../Web/Uploads/", SCANDIR_SORT_DESCENDING);
            $ifNewest = $ofFiles[0];
            unlink($ifNewest);
        } catch (Exception $exc) {
<<<<<<< HEAD
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
=======
            echo $exc->getTraceAsString();
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        }
    }

    /**
     * __move2
     * 
     * Upload a file to a FTP
     * 
     * @param File $oFile
     * @param Ftp $oFtp
     * @return Array result+message
     */
    private function __move2($oFile, $oFtp, $ifFtpName) {
        $aReturn = array("result" => FALSE, "message" => "The file wasn't uploaded");
        try {
            //Getting FTP Credentials
            $ftpServer = $oFtp->getServer();
            $username = $oFtp->getUsername();
            $password = $oFtp->getPassword();
<<<<<<< HEAD
            $ftpFolder = $oFtp->getFolder();
=======
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
            //Getting Input File information
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $ifFile = __DIR__ . "/../Web/Pendings/$ifName.$ifExtension";
            //Making the connection
            $ftpConn = ftp_connect($ftpServer) or die("Could not connect to $ftpServer");
            $login = ftp_login($ftpConn, $username, $password);
            //Making a Backup for the file
<<<<<<< HEAD
            $aBkFile = $this->bkFile($oFile);
=======
            $aBkFile = $this->__bkFile($oFile);
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
            if (!$aBkFile["result"]) {
                $sMessage = $aBkFile["message"];
                $aReturn = array("result" => FALSE, "message" => $sMessage);
            } else {
<<<<<<< HEAD
                if (ftp_put($ftpConn, "$ftpFolder/$ifFtpName.txt", $ifFile, FTP_ASCII)) {
                    $this->clean($oFile);
                    $aReturn = array("result" => TRUE, "message" => "Successfully uploaded $ifFile");
                } else {
                    $this->cleanNewest();
=======
                if (ftp_put($ftpConn, "pearson/$ifFtpName.txt", $ifFile, FTP_ASCII)) {
                    $this->__clean($oFile);
                    $aReturn = array("result" => TRUE, "message" => "Successfully uploaded $ifFile");
                } else {
                    $this->__cleanNewest();
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
                    $aReturn = array("result" => FALSE, "message" => "The file wasn't uploaded");
                }
            }
            ftp_close($ftpConn);
        } catch (Exception $exc) {
<<<<<<< HEAD
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

=======
            echo $exc->getTraceAsString();
        }
        return $aReturn;
    }
    
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
    /**
     * sendReport
     * 
     * Create and send a file to a specific FTP base on the inputs
     * 
     * @param File $oFile
     * @param String $delimiter
     * @param Ftp $oFtp
     * @param String $ifFtpName
     * @return Array result+message
     */
    public function sendReport($oFile, $delimiter, $oFtp, $ifFtpName) {
        $aReturn = array("result" => FALSE, "message" => "An error has occurr");
        try {
<<<<<<< HEAD
            $aCreateFile = $this->create($oFile, $delimiter);
=======
            $aCreateFile = $this->__create($oFile, $delimiter);
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
            if ($aCreateFile["result"]) {
                $aMove2 = $this->__move2($oFile, $oFtp, $ifFtpName);
                if ($aMove2["result"]) {
                    $aReturn = array("result" => TRUE, "message" => "The file has been sent to the FTP Server {$oFtp->getServer()}]");
                } else {
                    $aReturn = $aMove2;
                }
            } else {
                $aReturn = $aCreateFile;
            }
        } catch (Exception $exc) {
<<<<<<< HEAD
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
=======
            echo $exc->getTraceAsString();
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
        }
        return $aReturn;
    }

}

//$oFile = new File();
<<<<<<< HEAD
//$oInteraction = new InteractionsReport();
//$aInteraction = $oInteraction->getReport();
//$oFile->setName("interactionReport");
//$oFile->setExtension("txt");
//$lista = $aInteraction;
=======
//$oFile->setName("prueba");
//$oFile->setExtension("txt");
//$lista = array(
//    array('aaa', 'bbb', 'ccc', 'dddd'),
//    array('123', '456', '789'),
//    array('"aaa"', '"bbb"')
//);
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
//$oFile->setContent($lista);
//
//$delimiter = " ";
//
//$oFtp = new Ftp();
//$oFtp->setServer("172.25.185.22");
//$oFtp->setUsername("Josue");
//$oFtp->setPassword("123456");
<<<<<<< HEAD
//$oFtp->setFolder("pearson");
//
//$ifFtpName = "interactionstest";
=======
//
//$ifFtpName = "prueba_completa";
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
//$oFile = new File();
//$oFile->setName("archivo");
//$oFile->setExtension("txt");

//$oFileController = new FileController();
<<<<<<< HEAD
//echo json_encode($oFileController->create($oFile, $delimiter));
//echo json_encode($oFileController->test($oFile, $delimiter));
//echo json_encode($oFileController->sendReport($oFile, $delimiter, $oFtp, $ifFtpName));
//echo json_encode($oFileController->__move2($oFile, $oFtp));
//echo json_encode($oFileController->bkFile($oFile));
=======
//echo json_encode($oFileController->test($oFile, $delimiter));
//echo json_encode($oFileController->sendReport($oFile, $delimiter, $oFtp, $ifFtpName));
//echo json_encode($oFileController->__move2($oFile, $oFtp));
//echo json_encode($oFileController->__bkFile($oFile));
>>>>>>> 52d30e04370619f9ed44f71849c0c45df12ed975
