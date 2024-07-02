<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\SqlServerConnector;
use PDO;

class CustomSqlServerConnector extends SqlServerConnector
{
    /**
     * Override the default options array to prevent the SQLSRV error:
     * SQLSTATE[IMSSP]: An invalid attribute was designated on the PDO object.
     * 
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
    ];
}