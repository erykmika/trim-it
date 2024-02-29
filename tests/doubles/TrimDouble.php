<?php

declare(strict_types=1);

namespace Controller;

use Database\Database;

/**
 * Double class used for testing TrimController
 * It is done to mock JSON request input data
 */
class TrimDouble extends TrimController
{
    /** @var string $input_stream Overriden input stream used for accessing JSON post data */
    protected readonly string $input_stream;

    /**
     * Initialize a double/test TrimController object
     */
    public function __construct(Database $db, string $input_stream)
    {
        $this->input_stream = $input_stream;
        $this->db = $db;
    }

    /**
     * Relax visibility of the method in the parent class for testing
     */
    public function trimUrl(string $url): string
    {
        return parent::trimUrl($url);
    }

    /**
     * Get characters available for generating hashes
     * 
     * @return string CHARS array of the parent class
     */
    public function getChars(): string
    {
        return parent::CHARS;
    }
}
