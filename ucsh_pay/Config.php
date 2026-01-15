<?php

namespace Ucsh_pay\Ucshpay;

class Config
{
    public string $HOSTNAME;
    public int $PORT;
    public string $USERNAME;
    public string $PASSWORD;
    public string $BANKING_DB;
    public string $OSDB;

    public function __construct()
    {
        $this->loadEnv(__DIR__.'/../.env');
        $this->HOSTNAME = getenv('DB_HOST') ?: '127.0.0.1';
        $this->PORT = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;
        $this->USERNAME = getenv('DB_USER') ?: 'dom';
        $this->PASSWORD = getenv('DB_PASS') ?: 'domak';
        $this->BANKING_DB = getenv('DB_BANK') ?: 'ucsh_pay_db';
        $this->OSDB = getenv('OSDB') ?: '';
    }

    private function loadEnv($file)
    {
        if (!file_exists($file)) return;
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$name, $value] = explode('=', $line, 2);
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}
