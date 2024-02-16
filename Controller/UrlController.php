<?php

declare(strict_types=1);

namespace Controller;

use Enum\HttpMethod;

/**
 * Controller for handling URL shortening requests
 */
class UrlController extends Controller
{
    /**
     * Handle the request related to shortening a URL
     * 
     * @param array $endpoint Array of the endpoint URI segments handled by this controller
     * @param HttpMethod $method HTTP method of the request
     */
    protected function handleRequest(array $endpoint, HttpMethod $method)
    {
        // TODO
    }

    /**
     * Handle URL shortening request
     */
    private function handleShortening()
    {
        // TODO
    }
}
