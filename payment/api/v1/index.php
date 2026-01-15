<?php

use Ucsh_pay\Ucshpay\Payment;
use Ucsh_pay\Ucshpay\PaymentType;

require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/create_account.php';
require_once __DIR__ . '/payment_service.php';

$base_api_endpoint = '/payment/api/v1/';
$reqeust_uri = $_SERVER['REQUEST_URI'];

$api_endpoint = str_replace($base_api_endpoint, '', $reqeust_uri);
header('Content-Type: application/json');
switch ($api_endpoint) {
    case "account/create":
        create_account();
        break;
    case "payment/debit":
        payment_service(PaymentType::DEBIT);
        break;
    case "payment/credit":
        payment_service(PaymentType::CREDIT);
        break;
    default:
        echo json_encode([
            "status" => "success",
            "message" => "Hello from payment gateway sever"
        ]);
        break;
}
