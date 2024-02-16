<?php

declare(strict_types=1);

namespace Model;

use Database\Database;

/**
 * Abstract class representing a Model
 */
abstract class Model
{
    /** @var Database $db Database object */
    protected Database $db;

    /**
     * Create a Model object, set the reference to a database
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
