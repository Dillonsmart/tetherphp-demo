<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Record;
use TetherPHP\framework\Exceptions\HttpNotFoundException;

class Edit extends Domain
{
    public function __construct(
        private readonly Notes $notes,
        private readonly string $id,
    ) {
    }

    public function handle(): Record
    {
        $note = $this->notes->find($this->id) ?? throw new HttpNotFoundException();

        return new Record($this->id, ['title' => $note['title'], 'body' => $note['body']]);
    }
}
