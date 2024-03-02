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

    /** @var string[] allowed_fields Names of fields that can be manipulated within a model */
    protected const ALLOWED_FIELDS = [];

    /**
     * Create a Model object, set the reference to a database
     */
    public final function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Verify if given fields are allowed in this model
     * 
     * @param string[] $fields Array of fields to be checked
     * @return bool Are the fields allowed
     */
    protected function verifyFields(array $fields): bool
    {
        return empty(array_diff($fields, static::ALLOWED_FIELDS));
    }
}
