<?php

declare(strict_types=1);

use Controller\TrimDouble;
use Database\Database;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

final class TrimTest extends TestCase
{
    /**
     * @var Database $db Database object used for testing
     */
    private Database $db;

    /**
     * @var ?TrimDouble $controller Controller object used for testing
     */
    private ?TrimDouble $controller;

    protected function setUp(): void
    {
        $this->db = new Database(
            HOST,
            DBNAME,
            USERNAME,
            PASSWORD
        );
    }

    protected function tearDown(): void
    {
        $this->controller = null;
    }

    /**
     * @covers \Controller\TrimController::trimUrl
     */
    public function testGeneratedHashHasDesiredLength(): void
    {
        $this->controller = new TrimDouble($this->db, '');
        $url = 'https://github.com/erykmika';
        $hash = $this->controller->trimUrl($url);
        assertSame(7, strlen($hash));
    }

    /**
     * @covers \Controller\TrimController::trimUrl
     */
    public function testCharactersAreWithinDomain(): void
    {
        $this->controller = new TrimDouble($this->db, '');
        $url = 'https://github.com/erykmika/trim-it';
        $hash = $this->controller->trimUrl($url);
        $character_domain = $this->controller->getChars();
        $split = str_split($hash);
        $test_fn = function ($split, $character_domain) {
            foreach ($split as $character) {
                if (strpos($character_domain, $character) === false) {
                    return false;
                }
            }
            return true;
        };
        assertTrue($test_fn($split, $character_domain));
    }

    /**
     * @covers \Controller\TrimController::validateUrlPostData
     */
    public function testUrlPostDataCanBeValidated(): void
    {
        $this->controller = new TrimDouble($this->db, 'https://github.com/erykmika/trim-it');
        assertTrue(($this->controller->validateUrlPostData() !== false));
    }

    /**
     * @covers \Controller\TrimController::validateUrlPostData
     */
    public function testIncorrectUrlIsRejected(): void
    {
        $this->controller = new TrimDouble($this->db, '//github.com/erykmika/trim-it');
        assertTrue(($this->controller->validateUrlPostData() === false));
    }
}
