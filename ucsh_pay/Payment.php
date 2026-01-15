<?php

namespace Ucsh_pay\Ucshpay;

class Payment extends Database
{
    public string $user_uuid;
    public string $account_uuid;
    public int $pay_amount;

    public function __construct()
    {
        parent::__construct();
    }

    public function pay(): int
    {
        $account = new Account();
        $account->account_uuid = $this->account_uuid;
        $account->user_uuid = $this->user_uuid;

        $this->begin();

        try {
            // Lock & check balance
            $balance = $account->checkBalance();

            if ($balance < $this->pay_amount) {
                throw new \Exception("Insufficient funds");
            }

            // Deduct balance
            $account->deductBalance($this->pay_amount);

            // Record payment
            $stmt = $this->connection->prepare(Queries::$payment_insert["query"]);
            $stmt->bind_param(
                Queries::$payment_insert["types"],
                $this->user_uuid,
                $this->account_uuid,
                $this->pay_amount
            );

            $stmt->execute();
            $paymentId = $this->connection->insert_id;

            $this->commit();
            $stmt->close();

            return $paymentId;

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }
}
