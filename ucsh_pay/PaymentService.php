<?php
namespace Ucsh_pay\Ucshpay;

use Exception;

class PaymentService extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process a payment and log transaction
     */
    public function processPayment(string $user_uuid, string $account_uuid, int $amount, ?string $reference = null): int
    {
        $account = new Account();
        $account->connection = $this->connection; // share connection
        $account->account_uuid = $account_uuid;
        $account->user_uuid = $user_uuid;

        $this->begin();

        try {
            // Lock account and check balance
            $balance = $account->checkBalance();
            if ($balance < $amount) {
                throw new Exception("Insufficient balance");
            }

            // Deduct balance
            $account->deductBalance($amount);

            // Create payment record
            $payment = new Payment();
            $payment->connection = $this->connection;
            $payment->user_uuid = $user_uuid;
            $payment->account_uuid = $account_uuid;
            $payment->pay_amount = $amount;
            $paymentId = $payment->pay();

            // Log transaction
            $transaction = new Transaction();
            $transaction->connection = $this->connection;
            $transaction->account_uuid = $account_uuid;
            $transaction->type = 'debit';
            $transaction->amount = $amount;
            $transaction->balance_after = $account->checkBalance();
            $transaction->reference = $reference;
            $transaction->log();

            $this->commit();
            return $paymentId;

        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Credit balance to account and log transaction
     */
    public function creditAccount(string $account_uuid, int $amount, ?string $reference = null): int
    {
        $account = new Account();
        $account->connection = $this->connection;
        $account->account_uuid = $account_uuid;

        $this->begin();

        try {
            // Add balance
            $account->addBalance($amount);
            $balanceAfter = $account->checkBalance();

            // Log transaction
            $transaction = new Transaction();
            $transaction->connection = $this->connection;
            $transaction->account_uuid = $account_uuid;
            $transaction->type = 'credit';
            $transaction->amount = $amount;
            $transaction->balance_after = $balanceAfter;
            $transaction->reference = $reference;
            $transaction->log();

            $this->commit();
            return $balanceAfter;

        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
