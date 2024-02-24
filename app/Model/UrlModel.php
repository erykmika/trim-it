<?php

declare(strict_types=1);

namespace Model;

use Database\DatabaseException;

/**
 * URL model
 */
class UrlModel extends Model
{
    /**
     * @var string[] ALLOWED_FIELDS Fields that can be manipulated in UrlModel
     */
    protected const ALLOWED_FIELDS = ['url', 'hash'];

    /**
     * Insert a trimmed URL data row
     * 
     * @param array $data Associative array of URL data
     * @throws ModelException if provided data is incorrect
     * @throws DatabaseException if database operation fails
     * @return void
     */
    public function addUrl(array $data): void
    {
        if (!$this->verifyFields(array_keys($data)) || strlen($data['hash']) !== 7) {
            throw new ModelException('Incorrect URL data');
        }
        try {
            $this->db->query(
                <<<SQL
            INSERT INTO Url
            (url, hash)
            VALUES ('{$data['url']}', '{$data['hash']}');
            SQL
            );
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Get URL by given hash
     * 
     * @param string $hash Hash of the URL
     * @return string URL that is looked for
     */
    public function getUrlByHash(string $hash): string
    {
        try {
            $url = $this->db->query(<<<SQL
            SELECT url
            FROM Url
            WHERE hash = '$hash'
            SQL, true);
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
        if (empty($url)) {
            throw new ModelException('URL not found');
        }
        return $url[0]['url'];
    }
}
