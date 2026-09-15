<?php

declare(strict_types=1);

namespace Actions\Note;

use Actions\Action;
use App\Services;
use Domains\Note\Show as ShowDomain;
use Domains\Note\Notes;
use Responders\Note\Show as ShowResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Show extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new ShowDomain(new Notes($services->db), $request->params['id'] ?? '');
        $this->responder = new ShowResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
