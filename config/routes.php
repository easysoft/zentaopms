<?php
/*
 * The routes for API.
 */
$routes = array();

$routes['/tokens'] = 'tokens';

$routes['/products']         = 'products';
$routes['/products/:id']     = 'product';
$routes['/productlines']     = 'productLines';
$routes['/productlines/:id'] = 'productLine';

$routes['/products/:id/stories'] = 'stories';
$routes['/stories/:id']          = 'story';
$routes['/stories/:id/change']   = 'storyChange';

$routes['/products/:id/bugs'] = 'bugs';
$routes['/bugs/:id']          = 'bug';

$routes['/projects']     = 'projects';
$routes['/projects/:id'] = 'project';

$routes['/projects/:project/executions'] = 'executions';
$routes['/executions']                   = 'executions';
$routes['/executions/:id']               = 'execution';

$routes['/executions/:execution/tasks'] = 'tasks';
$routes['/tasks/:id']                   = 'task';
$routes['/tasks/:id/assignto']          = 'taskAssignTo';
$routes['/tasks/:id/start']             = 'taskStart';
$routes['/tasks/:id/finish']            = 'taskFinish';

$routes['/users']     = 'users';
$routes['/users/:id'] = 'user';
$routes['/user']      = 'user';

$routes['/programs']     = 'programs';
$routes['/programs/:id'] = 'program';

$config->routes = $routes;
