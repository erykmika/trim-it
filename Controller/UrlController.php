<?php

declare(strict_types=1);

namespace Controller;

use Enum\HttpMethod;
use Model\ModelException;
use Model\UrlModel;

/**
 * Controller for handling URL shortening requests
 */
class UrlController extends Controller
{
    /** @var string CHARS Characters to be used in generating shortened URL  */
    private const CHARS = "ab0cd1ef2gh3ij4kl5mn6op7qr8st9uv0wxyz";

    /**
     * Handle the request related to shortening a URL
     * 
     * @param array $endpoint Array of the endpoint URI segments handled by this controller
     * @param HttpMethod $method HTTP method of the request
     * @return never
     */
    protected function handleRequest(array $endpoint, HttpMethod $method): never
    {
        if ($endpoint === ['generate'] && $method === HttpMethod::POST) {
            $this->handleShortening();
        }
    }

    /**
     * Shorten URL and generate its 7-characters long hash
     * Use the haval224,4 algorithm that produces 224-bit long hashes
     * Use 4-byte chunks of it to generate the hash
     * 
     * @param string $url
     * @return string Hash of the shortened URL  
     */
    private function shortenUrl(string $url): string
    {
        $url = $_POST['url'];
        $haval_hash = hash('haval224,4', $url);
        $haval_chunks = str_split($haval_hash, 8);

        // Get decimal representations of the next chunks
        $haval_chunks = array_map(
            fn (string $chunk) => hexdec($chunk),
            $haval_chunks
        );

        // Use time as seed
        $time_seed = time();
        // Number of available characters
        $char_num = strlen(self::CHARS);

        // Resulting URL hash
        $url_hash = "";
        // XOR each chunk and time seed, use the remainder for indexing CHARS
        foreach ($haval_chunks as $chunk) {
            $char_index = ($chunk ^ $time_seed) % $char_num;
            $url_hash .= self::CHARS[$char_index];
        }
        return $url_hash;
    }

    /**
     * Check if the URL 'url' provided within POST data is set and correct
     * 
     * @return bool is data set and correct
     */
    private function validateUrlPostData(): bool
    {
        return (isset($_POST['url']) && !empty($_POST['url']) &&
            filter_var($_POST['url'], FILTER_VALIDATE_URL) !== FALSE);
    }

    /**
     * Handle URL shortening request
     * 
     * @return never
     */
    private function handleShortening(): never
    {
        if (!$this->validateUrlPostData()) {
            $this->sendResponse(404, false);
        }

        $url = $_POST['url'];
        $url_hash = $this->shortenUrl($url);

        try {
            $url_model = $this->getModel("\\" . UrlModel::class);
            $url_model->addUrl([
                'url' => $url,
                'hash' => $url_hash
            ]);
        } catch (ModelException $e) {
            $this->sendResponse(500, false);
        }

        $this->sendResponse(200, true, [
            'status' => 'success',
            'hash' => $url_hash
        ]);
    }
}
