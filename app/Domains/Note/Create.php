<?php

declare(strict_types=1);

namespace Domains\Note;

use Domains\Domain;
use Domains\Note\Results\Record;

class Create extends Domain
{
    public function handle(): Record
    {
        // an empty record: the form for a note that does not exist yet
        return new Record('', ['title' => '', 'body' => '']);
    }
}
