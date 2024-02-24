<?php

declare(strict_types=1);

use Model\UrlModel;
use Database\Database;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

final class UrlModelTest extends TestCase
{
    private Database $db;

    private UrlModel $url_model;

    protected function setUp(): void
    {
        $this->db = new Database(
            HOST,
            DBNAME,
            USERNAME,
            PASSWORD
        );
        $this->url_model = new UrlModel($this->db);
        $this->db->query('DELETE FROM Url;');
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
}
