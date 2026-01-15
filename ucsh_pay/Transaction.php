<?php
namespace Ucsh_pay\Ucshpay;

use Exception;
class Transaction extends Database
{
    public string $transaction_uuid;
    public string $account_uuid;
    public string $type; // "credit" or "debit"
    public int $amount;
    public int $balance_after;
    public ?string $reference = null;
    public ?string $notes = null;

    public function __construct()
    {
        parent::__construct();
        $this->transaction_uuid = Utils::generateUUID();
    }

    public function toAssoc(){
        return [
            "transaction_uuid" => $this->transaction_uuid,
            "type" => $this->type,
            "amount" => $this->amount,
            "balance" => $this->balance_after,
            "reference" => $this->reference,
            "message" => $this->notes,
        ];
    }
    public function log(): int
    {
        $stmt = $this->connection->prepare(Queries::$transaction_insert["query"]);
        $stmt->bind_param(
            Queries::$transaction_insert["types"],
            $this->transaction_uuid,
            $this->account_uuid,
            $this->type,
            $this->amount,
            $this->balance_after,
            $this->reference,
            $this->notes
        );

        $stmt->execute();
        $insertId = $this->connection->insert_id;
        $stmt->close();
        return $insertId;
    }

    public function getHistory(string $account_uuid, int $limit = 50): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM transactions WHERE account_uuid = ? ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->bind_param("si", $account_uuid, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        $stmt->close();
        return $transactions;
    }

}
