<?php

use Ucsh_pay\Ucshpay\PaymentService;
use Ucsh_pay\Ucshpay\PaymentType;

function generateTransactionReference(): string
{
    return 'TXN' . date('YmdHis') . rand(100, 999);
}
function payment_service(PaymentType $paymentType)
{
    try {
        $account_uuid = $_POST['account_uuid'];
        $user_uuid = $_POST['user_uuid'] ?? null;
        $amount = $_POST["amount"];
        $reference = generateTransactionReference();
        $notes = $_POST["notes"] ?? "";

        $paymentService = new PaymentService();

        $response = [
            "status" => "success",
            "message" => "",
            "data" => []
        ];
        if ($paymentType::CREDIT) {
            $performID = $paymentService->creditAccount($user_uuid, $account_uuid, $amount, $reference, $notes);
            if ($performID > 0) {
                $response['data'] = $paymentService->transaction_log;
                $response['message'] = "Process Completed!";
                echo json_encode($response);
                exit;
            }
        }
        if ($paymentType::DEBIT) {
            $performID = $paymentService->processPayment($user_uuid, $account_uuid, $amount, $reference, $notes);
            if ($performID > 0) {
                $response['data'] = $paymentService->transaction_log;
                $response['message'] = "Process Completed!";
                echo json_encode($response);
                exit;
            }
        }

        $response['data'] = null;
        $response['message'] = "Error while processing service..";
        $response['status'] = "Failed";
        echo json_encode($response);
        exit;
    } catch (\Exception $e) {
        $response['data'] = null;
        $response['message'] = "Error: {$e->getMessage()}";
        $response['status'] = "Failed";
        echo json_encode($response);
        exit;
    }
}
