<?php

declare(strict_types=1);

namespace Actions\Note;

use Actions\Action;
use App\Services;
use Domains\Note\Store as StoreDomain;
use Domains\Note\Notes;
use Responders\Note\Store as StoreResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Store extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new StoreDomain(new Notes($services->db), $request->payload);
        $this->responder = new StoreResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
