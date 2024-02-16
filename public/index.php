<?php

declare(strict_types=1);

// API entry point

use Database\Database;
use Enum\HttpMethod;

// Import configuration, create a proper database object
try {
    require_once __DIR__ . '/../include/bootstrap.php';
    $db = new Database(HOST, DBNAME, USERNAME, PASSWORD);
} catch (Throwable $t) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

if (empty($_SERVER['PATH_INFO'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

// Extract URI segments and determine request method
$uri = explode('/', ltrim($_SERVER['PATH_INFO'], '/'));
$method_str = strtoupper($_SERVER['REQUEST_METHOD']);

// Map the request method to a correct enum case
try {
    $method = match ($method_str) {
        'GET' => HttpMethod::GET,
        'POST' => HttpMethod::POST,
        'PUT' => HttpMethod::PUT,
        'DELETE' => HttpMethod::DELETE
    };
} catch (UnhandledMatchError $err) {
    // If the request method not valid
    header('HTTP/1.0 404 Not Found');
    exit();
}

if (array_key_exists($uri[0], ROUTES)) {
    $controller_name = "Controller\\" . ROUTES[$uri[0]];
} else {
    // Controller not found
    header('HTTP/1.0 404 Not Found');
    exit();
}
// Instantiate the right controller, pass endpoint segments to it
$controller = new $controller_name($db, array_slice($uri, 1), $method);
