<?php

use TetherPHP\Router;

/*
 * Where a request goes. `routes/middleware.php` says what it passes through.
 *
 * These seven lines are what `tether make:resource Note --uri=/notes` printed.
 * The generator does not edit this file: the route table is the one file a
 * reader has to be able to trust, so nothing but a person writes to it.
 * `/notes/create` is a static route and wins over `/notes/{id}` regardless of
 * order.
 */
return function (Router $router) {
    $router->get('/', Actions\Note\Index::class);

    $router->get('/notes', Actions\Note\Index::class);
    $router->get('/notes/create', Actions\Note\Create::class);
    $router->post('/notes', Actions\Note\Store::class);
    $router->get('/notes/{id}', Actions\Note\Show::class);
    $router->get('/notes/{id}/edit', Actions\Note\Edit::class);
    $router->put('/notes/{id}', Actions\Note\Update::class);
    $router->delete('/notes/{id}', Actions\Note\Destroy::class);
};
