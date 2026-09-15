<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Written;
use TetherPHP\framework\Exceptions\HttpNotFoundException;

class Destroy extends Domain
{
    public function __construct(
        private readonly Notes $notes,
        private readonly string $id,
    ) {
    }

    public function handle(): Written
    {
        if ($this->notes->find($this->id) === null) {
            throw new HttpNotFoundException();
        }

        $this->notes->delete($this->id);

        return new Written($this->id);
    }
}
