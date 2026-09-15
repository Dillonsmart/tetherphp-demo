<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Invalid;
use Domains\Note\Results\Written;

class Store extends Domain
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private readonly Notes $notes,
        private readonly array $payload,
    ) {
    }

    public function handle(): Written|Invalid
    {
        $attributes = Attributes::fromPayload($this->payload);

        if (!$attributes->isValid()) {
            return new Invalid('', $attributes->values, $attributes->errors);
        }

        return new Written($this->notes->insert($attributes->values['title'], $attributes->values['body']));
    }
}
