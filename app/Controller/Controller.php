<?php

declare(strict_types=1);

namespace Controller;

use Database\Database;
use Enum\HttpMethod;
use Model\Model;
use Throwable;

/**
 * Abstract class representing a Controller
 */
abstract class Controller
{
    /** @var Database $db Database object  */
    protected Database $db;

    /**
     * Create a Controller object, set the reference to a database
     * Call a request handler method
     * 
     * @param Database $db Database object
     * @param array $endpoint Array of endpoint URI segments handled by the Controller
     * @param HttpMethod $method HTTP method used
     */
    public final function __construct(Database $db, array $endpoint, HttpMethod $method)
    {
        $this->db = $db;
        $this->handleRequest($endpoint, $method);
    }

    /**
     * Get an instance of the particular Model class
     * 
     * @param string $class_name Fully qualified class name of the given Model
     * @return Model The Model object
     */
    protected final function getModel(string $class_name): Model
    {
        try {
            if (!(is_subclass_of($class_name, Model::class))) {
                throw new ControllerException('Class not a Model');
            }
            $model = new $class_name($this->db);
        } catch (Throwable $t) {
            $this->sendResponse(404, false);
        }
        return $model;
    }

    /**
     * Handle a request sent to a controller
     * 
     * @param array $endpoint Array of URI segment of the endpoint
     * @param HttpMethod $method HTTP method of the request
     * @return never
     */
    abstract protected function handleRequest(array $endpoint, HttpMethod $method): never;

    /**
     * Send HTTP response to client, exit the script
     * 
     * @param int $code HTTP response status code
     * @param bool $status 'true' on success, 'false' otherwise
     * @param string[] $data Associative array of response data, empty by default
     * @return never
     */
    protected final function sendResponse(int $code, bool $status, array $data = []): never
    {
        http_response_code($code);
        $status_msg = $status ? 'success' : 'failure';
        echo json_encode(array_merge(['status' => $status_msg], $data));
        exit();
    }
}
