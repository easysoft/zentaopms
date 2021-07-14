<?php
/*
 * The routes for API.
 */
$routes = array();

$routes['/tokens'] = 'tokens';

$routes['/programs'] = 'programs';
$routes['/programs/:id'] = 'program';

$routes['/projects']     = 'projects';
$routes['/projects/:id'] = 'project';

$routes['/products']         = 'products';
$routes['/products/:id']     = 'product';
$routes['/productlines']     = 'productLines';
$routes['/productlines/:id'] = 'productLine';

$routes['/executions/:execution/tasks'] = 'tasks';
$routes['/tasks/:id']                   = 'task';
$routes['/tasks/:id/assignto']          = 'taskAssignTo';
$routes['/tasks/:id/start']             = 'taskStart';
$routes['/tasks/:id/finish']            = 'taskFinish';

$config->routes = $routes;
