<?php

declare(strict_types=1);

namespace Responders\Note;

use Domains\Note\Results\Invalid;
use Domains\Note\Results\Written;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Store extends Responder
{
    /**
     * A write answers with a redirect; a refused one re-renders the form with
     * what was typed and why it was refused. The status comes off the type.
     */
    public function __invoke(Written|Invalid $result): Response
    {
        if ($result instanceof Invalid) {
            return $this->view('pages.note.create', [
                'id' => '',
                'attributes' => $result->attributes,
                'errors' => $result->errors,
            ], 422);
        }

        return Response::redirect('/notes/' . $result->id, 303);
    }
}
