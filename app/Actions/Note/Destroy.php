<?php

declare(strict_types=1);

namespace Actions\Note;

use Actions\Action;
use App\Services;
use Domains\Note\Destroy as DestroyDomain;
use Domains\Note\Notes;
use Responders\Note\Destroy as DestroyResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Destroy extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new DestroyDomain(new Notes($services->db), $request->params['id'] ?? '');
        $this->responder = new DestroyResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
