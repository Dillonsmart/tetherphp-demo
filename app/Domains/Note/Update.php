<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Invalid;
use Domains\Note\Results\Written;
use TetherPHP\framework\Exceptions\HttpNotFoundException;

class Update extends Domain
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private readonly Notes $notes,
        private readonly string $id,
        private readonly array $payload,
    ) {
    }

    public function handle(): Written|Invalid
    {
        if ($this->notes->find($this->id) === null) {
            throw new HttpNotFoundException();
        }

        $attributes = Attributes::fromPayload($this->payload);

        if (!$attributes->isValid()) {
            return new Invalid($this->id, $attributes->values, $attributes->errors);
        }

        $this->notes->update($this->id, $attributes->values['title'], $attributes->values['body']);

        return new Written($this->id);
    }
}
