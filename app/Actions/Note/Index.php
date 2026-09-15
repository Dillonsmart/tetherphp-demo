<?php

declare(strict_types=1);

namespace Actions\Note;

use Actions\Action;
use App\Services;
use Domains\Note\Index as IndexDomain;
use Domains\Note\Notes;
use Responders\Note\Index as IndexResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Index extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new IndexDomain(new Notes($services->db));
        $this->responder = new IndexResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
