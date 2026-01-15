<?php

namespace Ucsh_pay\Ucshpay;

use mysqli;

class Database extends Config 
{
    protected mysqli $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = new mysqli(
            $this->HOSTNAME,
            $this->USERNAME,
            $this->PASSWORD,
            $this->BANKING_DB,
            $this->PORT
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
