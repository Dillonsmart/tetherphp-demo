<?php

declare(strict_types=1);

namespace Responders\Note;

use Domains\Note\Results\Invalid;
use Domains\Note\Results\Written;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Update extends Responder
{
    public function __invoke(Written|Invalid $result): Response
    {
        if ($result instanceof Invalid) {
            return $this->view('pages.note.edit', [
                'id' => $result->id,
                'attributes' => $result->attributes,
                'errors' => $result->errors,
            ], 422);
        }

        return Response::redirect('/notes/' . $result->id, 303);
    }
}
