<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domains\Note\Attributes;
use PHPUnit\Framework\TestCase;

/**
 * The rules a note must meet, tested with no database, no Kernel and no
 * request — the payload is an array and the answer is a value.
 */
class AttributesTest extends TestCase
{
    public function testATitleAndBodyAreTrimmedAndAccepted(): void
    {
        $attributes = Attributes::fromPayload(['title' => '  Milk ', 'body' => " Two pints\n"]);

        $this->assertTrue($attributes->isValid());
        $this->assertSame(['title' => 'Milk', 'body' => 'Two pints'], $attributes->values);
    }

    public function testATitleIsRequired(): void
    {
        $attributes = Attributes::fromPayload(['title' => '   ', 'body' => 'x']);

        $this->assertFalse($attributes->isValid());
        $this->assertSame(['title' => 'A note needs a title.'], $attributes->errors);
    }

    public function testTheBodyMayBeEmpty(): void
    {
        $this->assertTrue(Attributes::fromPayload(['title' => 'Milk'])->isValid());
    }

    public function testATitleHasALengthLimit(): void
    {
        $attributes = Attributes::fromPayload(['title' => str_repeat('a', Attributes::TITLE_LENGTH + 1)]);

        $this->assertArrayHasKey('title', $attributes->errors);
        $this->assertTrue(Attributes::fromPayload(['title' => str_repeat('a', Attributes::TITLE_LENGTH)])->isValid());
    }

    /**
     * A form field is a string, but a JSON body can send anything. A value
     * that is not a scalar is treated as absent rather than cast to "Array".
     */
    public function testANonScalarValueIsTreatedAsAbsent(): void
    {
        $attributes = Attributes::fromPayload(['title' => ['nested'], 'body' => 'x']);

        $this->assertSame(['title' => 'A note needs a title.'], $attributes->errors);
    }
}
