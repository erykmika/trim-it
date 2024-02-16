<?php

declare(strict_types=1);

namespace Enum;

/**
 * Backed enum for representing HTTP methods in a unified way
 */
enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
