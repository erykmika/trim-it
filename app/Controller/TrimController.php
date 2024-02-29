<?php

declare(strict_types=1);

namespace Controller;

use Database\Database;
use Database\DatabaseException;
use Enum\HttpMethod;
use Model\ModelException;
use Model\UrlModel;

/**
 * Controller for handling URL trimming requests
 */
class TrimController extends Controller
{
    /** @var string CHARS Characters to be used in generating trimmed URL  */
    protected const CHARS = "ab0cd1ef2gh3ij4kl5mn6op7qr8st9uv0wxyz";

    /** @var string input_stream Stream used for accessing JSON data, helpful in test doubles */
    protected readonly string $input_stream;

    /**
     * Initialize the TrimController object
     * Specify the input stream for request data
     */
    public function __construct(Database $db, array $endpoint, HttpMethod $method)
    {
        $this->input_stream = 'php://input';
        parent::__construct($db, $endpoint, $method);
    }

    /**
     * Handle the request related to trimming a URL
     * 
     * @param array $endpoint Array of the endpoint URI segments handled by this controller
     * @param HttpMethod $method HTTP method of the request
     * @return never
     */
    protected function handleRequest(array $endpoint, HttpMethod $method): never
    {
        if ($method === HttpMethod::POST) {
            $this->handleTrimming();
        } else {
            $this->sendResponse(404, false);
        }
    }

    /**
     * Trim URL and generate its 7-characters long hash
     * Use the haval224,4 algorithm that produces 224-bit long hashes
     * Use 4-byte chunks of it to generate the hash
     * 
     * @param string $url
     * @return string Hash of the trimmed URL  
     */
    protected function trimUrl(string $url): string
    {
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
     * Check if the URL 'url' provided within JSON POST data is set and correct
     * Return it if so, return false otherwise
     * 
     * @return mixed URL POST data given it's correct, false otherwise
     */
    protected function validateUrlPostData(): mixed
    {
        $data = $this->getPostJsonData();
        if (
            !isset($data['url']) || empty($data['url']) ||
            filter_var($data['url'], FILTER_VALIDATE_URL) === FALSE
        ) {
            return false;
        } else {
            return $data;
        }
    }

    /**
     * Handle URL trimming request
     * 
     * @return never
     */
    protected function handleTrimming(): never
    {
        $request_data = $this->validateUrlPostData();
        if ($request_data === false) {
            $this->sendResponse(404, false);
        }

        $url = $request_data['url'];
        $url_hash = $this->trimUrl($url);

        try {
            $url_model = $this->getModel(UrlModel::class);
            $url_model->addUrl([
                'url' => $url,
                'hash' => $url_hash
            ]);
        } catch (ModelException $e) {
            $this->sendResponse(404, false);
        } catch (DatabaseException $e) {
            $this->sendResponse(503, false);
        }

        $this->sendResponse(200, true, [
            'hash' => $url_hash
        ]);
    }

    /**
     * Get POST data from JSON-encoded input stream
     * 
     * @return string[] Resulting array
     */
    protected function getPostJsonData(): array
    {
        $json = file_get_contents($this->input_stream);
        $data = json_decode($json, true);
        return $data;
    }
}
