<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class RequestValidator
{
    private $ci;

    const REQUIRED_PAY_REQUEST_KEYS = [
        'timestamp',
        'requestId',
        'email',
        'token',
        'orderData' => [
            'amount',
            'currency',
            'reference',
        ],
    ];

    const REQUIRED_REFUND_REQUEST_KEYS = [
        'timestamp',
        'requestId',
        'email',
        'token',
        'orderData' => [
            'amount',
            'currency',
            'reference',
        ],
    ];

    const REQUIRED_GET_BALANCE_REQUEST_KEYS = [
        'timestamp',
        'requestId',
        'email',
        'token',
    ];


    const REQUIRED_GET_CARD_DATA_REQUEST_KEYS = [
        'timestamp',
        'requestId',
        'email',
        'token',
    ];

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->model('RequestValidatorModel');
        $this->ci->load->model('CardDataModel');
    }

    /**
     * @param array $requestData
     * @param $format
     * @throws Exception
     */
    public function validateRequestStructure(array $requestData, $format)
    {
        foreach ($format as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
            }

            if (is_array($value)) {
                $this->validateRequestStructure($requestData[$key], $value);
            }

            if (!isset($requestData[$key])) {
                throw new Exception("Parameter '$value' is missing");
            }
        }
    }

    /**
     * @param $email
     * @throws Exception
     */
    public function validateRequestEmail($email)
    {
        if (empty($this->ci->RequestValidatorModel->checkUserEmail($email))) {
            throw new Exception("No user associated with the sent token");
        }
    }

    /**
     * @param $token
     * @throws Exception
     */
    public function validateUserToken($token, $email)
    {
        if (empty($this->ci->RequestValidatorModel->checkUserToken($token, $email))) {
            throw new Exception("No user associated with the sent token");
        }
    }

    /**
     * @param $email
     * @param $requestCredentials
     * @throws Exception
     */
    public function validateRequestCredentials($email, $requestCredentials, $token)
    {
        $authCredentials = $this->ci->RequestValidatorModel->checkUserCredentials($email, $requestCredentials, $token);

        if (empty($authCredentials)) {
            throw new Exception('Authentication failed');
        }
    }

    /**
     * @param $email
     * @param $amount
     * @throws Exception
     */
    public function validateAmount($email, $amount)
    {
        if ($amount > $this->ci->CardDataModel->getUserBalanceByEmail($email)) {
            throw new Exception('Insufficient funds!');
        }
    }

    /**
     * @param $email
     * @param $currency
     * @throws Exception
     */
    public function validateCurrency($email, $currency)
    {
        if ($currency !== $this->ci->CardDataModel->getUserBalanceCurrencyByEmail($email)) {
            throw new Exception('Currency not supported!');
        }
    }
}
