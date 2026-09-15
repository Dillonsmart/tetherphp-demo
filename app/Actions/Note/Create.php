<?php

declare(strict_types=1);

namespace Actions\Note;

use Actions\Action;
use App\Services;
use Domains\Note\Create as CreateDomain;
use Responders\Note\Create as CreateResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Create extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        $this->domain = new CreateDomain();
        $this->responder = new CreateResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
