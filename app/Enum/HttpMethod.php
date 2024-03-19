<?php

declare(strict_types=1);

namespace Enum;

/**
 * An enum for representing HTTP methods in a unified way
 */
enum HttpMethod
{
    case GET;
    case POST;
    case PUT;
    case DELETE;
}
