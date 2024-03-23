<?php

declare(strict_types=1);

namespace Controller;

use Database\DatabaseException;
use Enum\HttpMethod;
use Model\ModelException;
use Model\UrlModel;

/**
 * Controller for handling URL retrieve requests
 */
class UrlController extends Controller
{
    /**
     * Validate and handle the URL retrieve request
     * 
     * @param string[] $endpoint Array of URI segments handled by this controller
     * @param HttpMethod $method HTTP method of the request
     * @return never
     */
    protected function handleRequest(array $endpoint, HttpMethod $method): never
    {
        if (count($endpoint) === 1 && strlen($endpoint[0]) === 7 && $method === HttpMethod::GET) {
            $this->getUrl($endpoint[0]);
        } else {
            $this->sendResponse(404, false);
        }
    }

    /**
     * Get URL specified by given hash and send it to client
     * 
     * @param string $hash Hash of a previously trimmed URL
     * @return never
     */
    private function getUrl(string $hash): never
    {
        try {
            $url_model = $this->getModel(UrlModel::class);
            $url = $url_model->getUrlByHash($hash);
        } catch (DatabaseException $e) {
            $this->sendResponse(503, false);
        } catch (ModelException $e) {
            $this->sendResponse(404, false);
        }
        if ($url === false) {
            $this->sendResponse(404, false);
        }
        $this->sendResponse(200, true, ['url' => $url]);
    }
}
