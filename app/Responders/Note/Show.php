<?php

declare(strict_types=1);

namespace Responders\Note;

use Domains\Note\Results\Record;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Show extends Responder
{
    /**
     * The one place this page's view variables are named.
     */
    public function __invoke(Record $result): Response
    {
        return $this->view('pages.note.show', [
            'id' => $result->id,
            'attributes' => $result->attributes,
        ]);
    }
}
