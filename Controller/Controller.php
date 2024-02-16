<?php

declare(strict_types=1);

namespace Controller;

use Database\Database;
use Enum\HttpMethod;

/**
 * Abstract class representing a Controller
 */
abstract class Controller
{
    /** @var Database $db Database object  */
    protected Database $db;

    /**
     * Create a Controller object, set the reference to a database
     * 
     * @param Database $db Database object
     * @param array $endpoint Array of endpoint URI segments handled by the Controller
     * @param HttpMethod $method HTTP method used
     */
    public function __construct(Database $db, array $endpoint, HttpMethod $method)
    {
        $this->db = $db;
        $this->handleRequest($endpoint, $method);
    }

    /**
     * Handle a request sent to a controller
     * 
     * @param array $endpoint Array of URI segment of the endpoint
     * @param HttpMethod $method HTTP method of the request
     * @return void
     */
    abstract protected function handleRequest(array $endpoint, HttpMethod $request);
}
