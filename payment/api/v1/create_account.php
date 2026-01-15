<?php
use Ucsh_pay\Ucshpay\Account;
use Ucsh_pay\Ucshpay\Utils;
function create_account(){

    $account_uuid = Utils::generateUUID();
    $account = new Account();
    $account->account_uuid = $account_uuid;
    $account->username = $_POST["username"];
    $account->balance = 0;
    $account->user_uuid = $_SESSION["user_uuid"];

    $account->createAccount();

    echo json_encode([
        "status" => "success",
        "message" => "Account Created Successful",
        "data" => $account->toJson()
    ]);
    exit;
}