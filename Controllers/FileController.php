<?php

include_once __DIR__ . '/../Entities/File.php';
include_once __DIR__ . '/../Libs/FileLib.php';
include_once __DIR__ . '/../Entities/Ftp.php';
include_once __DIR__ . '/MailController.php';

/**
 * Provide all function related with the management of files
 *
 * @author josuefrancisco
 */
class FileController {

    /**
     * create
     * 
     * Create a file base on the inputs.
     * 
     * @param File $oFile
     * @param String $delimiter
     * @return Array result+message
     */
    public function create($oFile, $delimiter) {
        $aReturn = array("result" => FALSE, "message" => "The file wasn't created");
        try {
            $oFileLib = new FileLib();
            $aReturn = $oFileLib->create($oFile, $delimiter);
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
     * bkFile
     * 
     * Make a backup for the file provided
     * 
     * @param File $oFile
     * @return Array result+message
     */
    private function bkFile($oFile) {
        $aReturn = array("result" => FALSE, "message" => "The file wasn't created");
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
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
        return $aReturn;
    }

    /**
     * clean
     * 
     * Delete a file
     * 
     * @param File $oFile
     */
    private function clean($oFile) {
        try {
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $ifFile = __DIR__ . "/../Web/Pendings/$ifName.$ifExtension";
            unlink($ifFile);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
        }
    }

    /**
     * cleanNewest
     * 
     * Delete the newest file in the folder Uploads
     * 
     */
    private function cleanNewest() {
        try {
            $ofFiles = scandir(__DIR__ . "/../Web/Uploads/", SCANDIR_SORT_DESCENDING);
            $ifNewest = $ofFiles[0];
            unlink($ifNewest);
        } catch (Exception $exc) {
            $sMsj = $exc->getMessage();
            $sLine = $exc->getLine();
            $sCode = $exc->getCode();
            $sFile = $exc->getFile();
            $sTrace = $exc->getTraceAsString();
            $oMail = new MailController();
            $oMail->sendEmail($sCode, $sMsj, $sFile, $sLine, $sTrace);
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
            $ftpFolder = $oFtp->getFolder();
            //Getting Input File information
            $ifName = $oFile->getName();
            $ifExtension = $oFile->getExtension();
            $ifFile = __DIR__ . "/../Web/Pendings/$ifName.$ifExtension";
            //Making the connection
            $ftpConn = ftp_connect($ftpServer) or die("Could not connect to $ftpServer");
            $login = ftp_login($ftpConn, $username, $password);
            //Making a Backup for the file
            $aBkFile = $this->bkFile($oFile);
            if (!$aBkFile["result"]) {
                $sMessage = $aBkFile["message"];
                $aReturn = array("result" => FALSE, "message" => $sMessage);
            } else {
                if (ftp_put($ftpConn, "$ftpFolder/$ifFtpName.txt", $ifFile, FTP_ASCII)) {
                    $this->clean($oFile);
                    $aReturn = array("result" => TRUE, "message" => "Successfully uploaded $ifFile");
                } else {
                    $this->cleanNewest();
                    $aReturn = array("result" => FALSE, "message" => "The file wasn't uploaded");
                }
            }
            ftp_close($ftpConn);
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
            $aCreateFile = $this->create($oFile, $delimiter);
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