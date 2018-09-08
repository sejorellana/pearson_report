<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../Libs/vendor/autoload.php';

/**
 * Description of MailController
 *
 * @author josuefrancisco
 */
class MailController {

    /**
     * parseSetup
     * 
     * Parse the setup file into an array
     * 
     * @return type
     */
    private function parseSetup() {
        try {
            $json = file_get_contents(__DIR__ . "/../Setup/mail.json");
            $aJson = json_decode($json, TRUE);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $aJson;
    }

    /**
     * sendEmail
     * 
     * Send an error email
     * 
     * @param String $code
     * @param String $message
     * @param String $file
     * @param String $line
     * @param String $trace
     */
    public function sendEmail($code, $message, $file, $line, $trace) {
        try {
            $aMail = $this->parseSetup();
            $from = $aMail["from"];
            $to = $aMail["to"];
            $replyTo = $aMail["replyTo"];
            $aCC = explode(",", $aMail["cc"]);
            $subject = $aMail["subject"];

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet = "UTF-8";
            $mail->SMTPSecure = 'tls';
            $mail->Host = $aMail["smtpHost"];
            $mail->Port = $aMail["smtpPort"];
            $mail->Username = $aMail["smtUser"];
            $mail->Password = $aMail["smtpPassword"];
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;

            $mail->setFrom($from);
            $mail->AddAddress($to);
            foreach ($aCC as $cc):
                $mail->AddAddress("$cc");
            endforeach;

            $mail->isHTML(true);
            $mail->Subject = "$subject";
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer";
            $hmltBody = "
            <html>
            <head>
              <title>An error has occur!</title>
            </head>
            <body>
              <table>
                <tr>
                  <th>Message</th><td>$message</td>
                </tr>
                <tr>
                  <th>Code</th><td>$code</td>
                </tr>
                <tr>
                  <th>File</th><td>$file</td>
                </tr>
                <tr>
                  <th>Linea</th><td>$line</td>
                </tr>
                <tr>
                  <th>Trace</th><td>$trace</td>
                </tr>
              </table>
            </body>
            </html>";
            $mail->Body = "$hmltBody";
            $mail->AddReplyTo("$replyTo", 'Information');
            $mail->IsHTML(true);
            $mail->send();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
