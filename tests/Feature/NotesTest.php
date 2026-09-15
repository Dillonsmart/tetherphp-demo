<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Every route of the resource, through the real Kernel and the real
 * routes/web.php, against an in-memory database. The contract of a web
 * application is request in, response out, and these assert on nothing else.
 */
class NotesTest extends TestCase
{
    public function testTheListIsEmptyToBeginWith(): void
    {
        $response = $this->get('/notes');

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('Nothing here yet', $response->body());
    }

    public function testTheRootIsTheList(): void
    {
        $this->assertStringContainsString('Nothing here yet', $this->get('/')->body());
    }

    public function testStoringANoteRedirectsToIt(): void
    {
        $response = $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $this->assertSame(303, $response->status());
        $this->assertSame('/notes/1', $response->headers()['Location'] ?? null);

        $shown = $this->get('/notes/1');
        $this->assertSame(200, $shown->status());
        $this->assertStringContainsString('Milk', $shown->body());
        $this->assertStringContainsString('Two pints', $shown->body());
    }

    public function testAStoredNoteAppearsInTheList(): void
    {
        $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $this->assertStringContainsString('Milk', $this->get('/notes')->body());
    }

    /**
     * A refused write is a 422 carrying the form back, with what was typed
     * still in it and the reason beside the field. Nothing was written.
     */
    public function testANoteWithoutATitleIsRefusedAndTheFormComesBack(): void
    {
        $response = $this->post('/notes', ['title' => '', 'body' => 'Orphaned body']);

        $this->assertSame(422, $response->status());
        $this->assertStringContainsString('A note needs a title.', $response->body());
        $this->assertStringContainsString('Orphaned body', $response->body());
        $this->assertStringContainsString('Nothing here yet', $this->get('/notes')->body());
    }

    public function testUpdatingANoteChangesItAndRedirectsBack(): void
    {
        $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $response = $this->put('/notes/1', ['title' => 'Oat milk', 'body' => 'One carton']);

        $this->assertSame(303, $response->status());
        $this->assertSame('/notes/1', $response->headers()['Location'] ?? null);
        $this->assertStringContainsString('Oat milk', $this->get('/notes/1')->body());
        $this->assertStringNotContainsString('Two pints', $this->get('/notes/1')->body());
    }

    public function testARefusedUpdateKeepsTheOriginal(): void
    {
        $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $this->assertSame(422, $this->put('/notes/1', ['title' => ''])->status());
        $this->assertStringContainsString('Milk', $this->get('/notes/1')->body());
    }

    public function testDeletingANoteRemovesItAndRedirectsToTheList(): void
    {
        $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $response = $this->delete('/notes/1');

        $this->assertSame(303, $response->status());
        $this->assertSame('/notes', $response->headers()['Location'] ?? null);
        $this->assertSame(404, $this->get('/notes/1')->status());
    }

    public function testANoteThatDoesNotExistIsA404OnEveryRoute(): void
    {
        $this->assertSame(404, $this->get('/notes/9')->status());
        $this->assertSame(404, $this->get('/notes/9/edit')->status());
        $this->assertSame(404, $this->put('/notes/9', ['title' => 'x'])->status());
        $this->assertSame(404, $this->delete('/notes/9')->status());
    }

    /**
     * A static route wins over a dynamic one of the same shape, so the create
     * form is not a note whose id is "create".
     */
    public function testTheCreateFormIsNotANoteCalledCreate(): void
    {
        $response = $this->get('/notes/create');

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('<form', $response->body());
    }

    public function testTheEditFormIsFilledIn(): void
    {
        $this->post('/notes', ['title' => 'Milk', 'body' => 'Two pints']);

        $body = $this->get('/notes/1/edit')->body();

        $this->assertStringContainsString('value="Milk"', $body);
        $this->assertStringContainsString('Two pints</textarea>', $body);
        $this->assertStringContainsString('name="_method" value="PUT"', $body);
    }

    public function testWhatAVisitorTypesIsEscaped(): void
    {
        $this->post('/notes', ['title' => '<script>alert(1)</script>', 'body' => 'x']);

        $body = $this->get('/notes/1')->body();

        $this->assertStringNotContainsString('<script>', $body);
        $this->assertStringContainsString('&lt;script&gt;', $body);
    }
}
