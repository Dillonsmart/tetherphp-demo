<?php

declare(strict_types=1);

namespace Domains\Note;

use PDO;

/**
 * Every query the Note feature runs, in one place.
 *
 * Not a Domain — a Domain is one operation with one answer, and five of them
 * read or write the same table. A plain collaborator holds the SQL and each
 * Domain is handed one, the way the site's dev log Domains share a Posts.
 *
 * @phpstan-type Row array{id: int, title: string, body: string, created_at: string, updated_at: string}
 */
final readonly class Notes
{
    public function __construct(private PDO $db)
    {
    }

    /**
     * Newest first, with enough of the body for a listing.
     *
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->db
            ->query('SELECT id, title, substr(body, 1, 120) AS excerpt, updated_at FROM notes ORDER BY updated_at DESC, id DESC')
            ->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM notes WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    public function insert(string $title, string $body): string
    {
        $this->db
            ->prepare('INSERT INTO notes (title, body) VALUES (:title, :body)')
            ->execute(['title' => $title, 'body' => $body]);

        return (string) $this->db->lastInsertId();
    }

    public function update(string $id, string $title, string $body): void
    {
        $this->db
            ->prepare("UPDATE notes SET title = :title, body = :body, updated_at = datetime('now') WHERE id = :id")
            ->execute(['id' => $id, 'title' => $title, 'body' => $body]);
    }

    public function delete(string $id): void
    {
        $this->db->prepare('DELETE FROM notes WHERE id = :id')->execute(['id' => $id]);
    }
}
