<?php

declare(strict_types=1);

namespace Domains\Note;

/**
 * The fields a note is made of, read off a request body and checked.
 *
 * Store and Update both take a payload and both apply the same rules, so the
 * rules live here rather than twice. There is no validation layer in the
 * framework; a small class per feature is the whole mechanism, and it stays
 * readable because it does nothing general.
 */
final readonly class Attributes
{
    public const int TITLE_LENGTH = 120;

    public const int BODY_LENGTH = 10_000;

    /**
     * @param array{title: string, body: string} $values
     * @param array<string, string>              $errors  field => message
     */
    private function __construct(
        public array $values,
        public array $errors,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        $title = trim(self::string($payload['title'] ?? ''));
        $body = trim(self::string($payload['body'] ?? ''));

        $errors = [];

        if ($title === '') {
            $errors['title'] = 'A note needs a title.';
        } elseif (mb_strlen($title) > self::TITLE_LENGTH) {
            $errors['title'] = sprintf('Keep the title to %d characters.', self::TITLE_LENGTH);
        }

        if (mb_strlen($body) > self::BODY_LENGTH) {
            $errors['body'] = sprintf('Keep the body to %d characters.', self::BODY_LENGTH);
        }

        return new self(['title' => $title, 'body' => $body], $errors);
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    private static function string(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }
}
