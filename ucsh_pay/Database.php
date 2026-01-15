<?php

namespace Ucsh_pay\Ucshpay;

use mysqli;

class Database
{
    protected mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            "127.0.0.1",
            "dom",
            "domak",
            "ucsh_pay_db",
            3306
        );

        if ($this->connection->connect_error) {
            throw new \Exception(
                "Database connection failed: " . $this->connection->connect_error
            );
        }

        $this->connection->set_charset("utf8mb4");
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    protected function begin(): void
    {
        $this->connection->begin_transaction();
    }

    protected function commit(): void
    {
        $this->connection->commit();
    }

    protected function rollback(): void
    {
        $this->connection->rollback();
    }
}
