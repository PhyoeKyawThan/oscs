<?php
namespace Ucsh_pay\Ucshpay;

class Queries
{
    public static array $create_pay_account = [
        "types" => "ssis",
        "query" => "INSERT INTO account (account_uuid, username, balance, user_uuid)
                    VALUES (?, ?, ?, ?)"
    ];

    public static array $check_balance = [
        "types" => "ss",
        "query" => "SELECT balance FROM account
                    WHERE account_uuid = ? AND user_uuid = ?
                    FOR UPDATE"
    ];

    public static array $add_balance = [
        "types" => "iss",
        "query" => "UPDATE account
                    SET balance = balance + ?
                    WHERE account_uuid = ? AND user_uuid = ?"
    ];

    public static array $deduct_balance = [
        "types" => "issi",
        "query" => "UPDATE account
                    SET balance = balance - ?
                    WHERE account_uuid = ? AND user_uuid = ?
                      AND balance >= ?"
    ];

    public static array $payment_insert = [
        "types" => "ssi",
        "query" => "INSERT INTO payment (user_uuid, account_uuid, pay_amount)
                    VALUES (?, ?, ?)"
    ];
    public static array $transaction_insert = [
    "types" => "sssis",
    "query" => "INSERT INTO transactions (transaction_uuid, account_uuid, type, amount, balance_after, reference)
                VALUES (?, ?, ?, ?, ?, ?)"
];

}
