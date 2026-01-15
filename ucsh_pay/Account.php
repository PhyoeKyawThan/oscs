<?php
namespace Ucsh_pay\Ucshpay;

class Account extends Database
{
    public string $account_uuid;
    public string $username;
    public int $balance;
    public string $user_uuid;

    public function __construct()
    {
        parent::__construct();
    }

    public function createAccount(): int
    {
        $stmt = $this->connection->prepare(Queries::$create_pay_account["query"]);
        $uuid = Utils::generateUUID();
        $stmt->bind_param(
            Queries::$create_pay_account["types"],
            $uuid,
            $this->username,
            $this->balance,
            $this->user_uuid
        );

        $this->begin();

        try {
            $stmt->execute();
            $id = $this->connection->insert_id;
            $this->commit();
            $stmt->close();
            return $id;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function checkBalance(): int
    {
        $stmt = $this->connection->prepare(Queries::$check_balance["query"]);
        $stmt->bind_param(
            Queries::$check_balance["types"],
            $this->account_uuid,
            $this->user_uuid
        );

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$result) {
            throw new \Exception("Account not found");
        }

        return (int)$result['balance'];
    }

    public function addBalance(int $amount): void
    {
        $stmt = $this->connection->prepare(Queries::$add_balance["query"]);
        $stmt->bind_param(
            Queries::$add_balance["types"],
            $amount,
            $this->account_uuid,
            $this->user_uuid
        );

        $stmt->execute();
        $stmt->close();
    }

    public function deductBalance(int $amount): void
    {
        $stmt = $this->connection->prepare(Queries::$deduct_balance["query"]);
        $stmt->bind_param(
            Queries::$deduct_balance['types'],
            $amount,
            $this->account_uuid,
            $this->user_uuid,
            $amount
        );

        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new \Exception("Insufficient balance");
        }

        $stmt->close();
    }
}
