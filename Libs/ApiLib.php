<?php

include_once __DIR__ . '/../Entities/Api.php';
include_once __DIR__ . '/../Entities/Credential.php';

/**
 * Provide the methods [GET][POST][PUT] to work with an API, this class only
 * support Web requests in JSON format
 *
 * @author jorellana
 */
class ApiLib {

    function __construct() {
        
    }

    /**
     * get
     * 
     * Make a get call of a specific API
     * 
     * @param Api $Api
     * @param Credential $Credential
     * @return Response It depends of the API 
     */
    public function get($Api, $Credential) {
        $return = FALSE;
        try {
            //Getting API properties 
            $url = $Api->getUrl();
            $user = $Credential->getUsername();
            $password = $Credential->getPassword();
            //Init curl object
            $curl = curl_init();

            //Get set up
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
            //Adding options
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "$user:$password");

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $return = curl_exec($curl);

            //Closing curl object
            curl_close($curl);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $return;
    }

    /**
     * post
     * 
     * Make a post call to a specific API
     * 
     * @param Api $Api
     * @param Credential $Credential
     * @return Result It depends of the API
     */
    public function post($Api, $Credential) {
        $return = FALSE;
        try {
            //Getting API properties 
            $url = $Api->getUrl();
            $data = $Api->getData();

            $user = $Credential->getUsername();
            $password = $Credential->getPassword();


            //Curl Option
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "$user:$password");

            //Post set up
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)))
            );

            $return = curl_exec($curl);
            //Closing curl object
            curl_close($curl);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $return;
    }

    /**
     * put
     * 
     * Make a PUT call to a specific API
     * 
     * @param Api $Api
     * @param Credential $Credential
     * @return Result It depends of the API
     */
    public function put($Api, $Credential) {
        $return = FALSE;
        try {
            //Getting API properties 
            $url = $Api->getUrl();
            $data = $Api->getData();

            $user = $Credential->getUsername();
            $password = $Credential->getPassword();


            //Curl Option
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "$user:$password");

            //Post set up
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)))
            );

            $return = curl_exec($curl);
            //Closing curl object
            curl_close($curl);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $return;
    }

}
