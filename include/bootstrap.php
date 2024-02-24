<?php

declare(strict_types=1);

// Define path for root directory
define('ROOT_DIR', __DIR__ . '/..');

// Require database configuration file
require_once(ROOT_DIR . '/include/db_config.php');

// Register API routes
require_once(ROOT_DIR . '/include/routes.php');

// Require autoloader
require_once(ROOT_DIR . '/vendor/autoload.php');

// Custom Error handler
set_exception_handler(fn (Error $err) => print('Error has occurred ' . $err->getMessage()));

/**
 * Not found response function for an API entry point
 * 
 * @return never 
 */
function notFoundResponse(): never
{
    http_response_code(404);
    echo json_encode(['status' => 'failure']);
    exit();
}
