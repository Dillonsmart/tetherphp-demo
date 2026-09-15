<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Collection;

class Index extends Domain
{
    public function __construct(private readonly Notes $notes)
    {
    }

    public function handle(): Collection
    {
        return new Collection($this->notes->all());
    }
}
