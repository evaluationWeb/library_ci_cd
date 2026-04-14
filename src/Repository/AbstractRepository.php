<?php

namespace App\Repository;

use App\Database\Mysql;

abstract class AbstractRepository
{
    protected \PDO $connect;

    public function __construct()
    {
        $this->connect = Mysql::connectBdd();
    }
}
