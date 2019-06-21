<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Restserver\Libraries\REST_Controller;

require(APPPATH . 'libraries/REST_Controller.php');

class ApiController extends REST_Controller
{
    const SUCCESS_HTTP_CODE = "200";
    const ERROR_HTTP_CODE = "400";

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('RequestValidator');
        $this->load->library('RequestProcessor');
    }

    public function balance_get()
    {
        $getData = $this->get();
        $token = $getData['token'];
        $email = $getData['email'];

        try {
            //TODO 1: Validate if the GET request contains the required parameters

            // Validate that the 'Authorization' header for API authentication is set correctly
            $this->requestvalidator->checkApiAuthentication($this->head('Authorization'), $email, $token);

            // Retrieve balance data from database for a specific user, based on its email
            $currentBalance = $this->requestprocessor->processGetBalanceRequest($email);

            // Set meta data + balance in the API response
            $apiResponse = $this->setApiMetaResponseForSuccess();
            $apiResponse['userData']['balance'] = $currentBalance;

        } catch (RequestValidatorException $exception) {
            $apiResponse = $this->setApiMetaResponseForError($exception->getMessage());
        }

        //TODO 1: let's send an appropriate HTTP code for our response
        $this->response($apiResponse);
    }

    public function pay_post()
    {
        $postData = $this->post();
        $email = $postData['email'];
        $token = $postData['token'];

        try {
            // Validate if the POST request contains the required parameters
            //TODO 6: call RequestValidator, it might have something to say here

            //TODO 7: add validations for currency and amount

            // Validate that the 'Authorization' header for API authentication is set correctly
            $this->requestvalidator->checkApiAuthentication($this->head('Authorization'), $email, $token);

            // Update the balance of the user based on its email
            //TODO 6: call the RequestProcessor

            //TODO 8: Set meta data in the API response


        } catch (RequestValidatorException $exception) {
            $apiResponse = $this->setApiMetaResponseForError($exception->getMessage());
            $httpCode = self::ERROR_HTTP_CODE;
        }

        // Use the same reference sent in the request to match the response of the API
        $apiResponse['orderData']['reference'] = $postData['orderData']['reference'];
        $this->response($apiResponse, $httpCode);
    }

    //TODO 9: create a refund method

    /**
     * @return array
     */
    private function setApiMetaResponseForSuccess()
    {
        $apiResponse['meta']['status'] = 'Ok';
        $apiResponse['meta']['message'] = 'Operation successful';

        return $apiResponse;
    }

    /**
     * @param string $errorMessage
     * @return array
     */
    private function setApiMetaResponseForError($errorMessage)
    {
        $apiResponse['meta']['status'] = 'Error';
        $apiResponse['meta']['message'] = $errorMessage;

        return $apiResponse;
    }

 }
