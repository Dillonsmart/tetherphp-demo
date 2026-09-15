<?php

declare(strict_types=1);

namespace Domains\Note\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * A write that was refused, with what was sent and what was wrong with it.
 *
 * The fifth shape. A feature that can refuse input answers with a different
 * class when it does, and the Responder picks the form and the status off the
 * type — the same way a miss is a different class from a hit. Carrying the
 * attributes back means the form is re-rendered with what the visitor typed.
 */
final readonly class Invalid implements DomainResult
{
    /**
     * @param array<string, mixed>  $attributes what was submitted
     * @param array<string, string> $errors     field => message
     */
    public function __construct(
        public string $id,
        public array $attributes,
        public array $errors,
    ) {
    }
}
