<?php

declare(strict_types=1);

use Model\UrlModel;
use Database\Database;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

final class UrlModelTest extends TestCase
{
    /**
     * @var Database $db Database object used for testing
     */
    private Database $db;

    /**
     * @var ?UrlModel $url_model UrlModel object used for testing
     */
    private ?UrlModel $url_model;

    protected function setUp(): void
    {
        $this->db = new Database(
            HOST,
            DBNAME,
            USERNAME,
            PASSWORD
        );
        $this->url_model = new UrlModel($this->db);
    }

    protected function tearDown(): void
    {
        $this->db->query('DELETE FROM Url;');
        $this->url_model = null;
    }

    /**
     * @covers \Model\UrlModel::addUrl
     */
    public function testUrlCanBeInserted(): void
    {
        $url = 'https://github.com/';
        $hash = 'x1b0a33';
        $this->url_model->addUrl([
            'url' => $url,
            'hash' => $hash
        ]);
        $fetched_url = $this->db->query("SELECT url FROM Url WHERE hash = '{$hash}'", true);
        assertEquals($url, $fetched_url[0]['url']);
    }

    /**
     * @covers \Model\UrlModel::getUrlByHash
     */
    public function testUrlCanBeRetrieved(): void
    {
        $url = 'https://docs.phpunit.de/';
        $hash = 'yas2bg0';
        $this->url_model->addUrl([
            'url' => $url,
            'hash' => $hash
        ]);
        $fetched_url = $this->url_model->getUrlByHash($hash);
        assertEquals($url, $fetched_url);
    }
}
