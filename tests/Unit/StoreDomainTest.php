<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domains\Note\Notes;
use Domains\Note\Results\Invalid;
use Domains\Note\Results\Written;
use Domains\Note\Store as StoreDomain;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * A Domain on its own: handed a Notes over an in-memory database and a
 * payload, it answers with one of two result types. No Kernel, no request.
 */
class StoreDomainTest extends TestCase
{
    private function notes(): Notes
    {
        $db = new PDO('sqlite::memory:', options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $db->exec((string) file_get_contents(__DIR__ . '/../../database/schema.sql'));

        return new Notes($db);
    }

    public function testAValidPayloadIsWrittenAndItsIdReturned(): void
    {
        $notes = $this->notes();

        $result = new StoreDomain($notes, ['title' => 'Milk', 'body' => 'Two pints'])->handle();

        $this->assertInstanceOf(Written::class, $result);
        $this->assertSame('1', $result->id);
        $this->assertSame('Milk', $notes->find('1')['title'] ?? null);
    }

    public function testAnInvalidPayloadWritesNothingAndSaysWhy(): void
    {
        $notes = $this->notes();

        $result = new StoreDomain($notes, ['title' => '', 'body' => 'Orphaned'])->handle();

        $this->assertInstanceOf(Invalid::class, $result);
        $this->assertSame(['title' => 'A note needs a title.'], $result->errors);
        $this->assertSame('Orphaned', $result->attributes['body']);
        $this->assertSame([], $notes->all());
    }
}
