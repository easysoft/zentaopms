<?php
/*
 * The routes for API.
 */
$routes = array();

$routes['/tokens'] = 'tokens';
$routes['/tabs/:module'] = 'tabs';

$routes['/configurations']       = 'configs';
$routes['/configurations/:name'] = 'config';

$routes['/programs/:id/products'] = 'products';
$routes['/products']              = 'products';
$routes['/products/:id']          = 'product';

$routes['/productlines']     = 'productLines';
$routes['/productlines/:id'] = 'productLine';

$routes['/productplans']       = 'productPlans';
$routes['/products/:id/plans'] = 'productPlans';
$routes['/productplans/:id']   = 'productPlan';

$routes['/releases']              = 'releases';
$routes['/product/:id/releases']  = 'releases';
$routes['/projects/:id/releases'] = 'projectreleases';
$routes['/releases/:id']          = 'release';

$routes['/stories']                = 'stories';
$routes['/products/:id/stories']   = 'stories';
$routes['/executions/:id/stories'] = 'executionStories';
$routes['/stories/:id']            = 'story';
$routes['/stories/:id/change']     = 'storyChange';

$routes['/products/:id/bugs'] = 'bugs';
$routes['/bugs']              = 'bugs';
$routes['/bugs/:id']          = 'bug';

$routes['/programs/:id/projects'] = 'projects';
$routes['/projects']              = 'projects';
$routes['/projects/:id']          = 'project';

$routes['/projects/:project/executions'] = 'executions';
$routes['/executions']                   = 'executions';
$routes['/executions/:id']               = 'execution';

$routes['/executions/:execution/tasks'] = 'tasks';
$routes['/tasks']                       = 'tasks';
$routes['/tasks/:id']                   = 'task';
$routes['/tasks/:id/assignto']          = 'taskAssignTo';
$routes['/tasks/:id/start']             = 'taskStart';
$routes['/tasks/:id/finish']            = 'taskFinish';

$routes['/users']     = 'users';
$routes['/users/:id'] = 'user';
$routes['/user']      = 'user';

$routes['/programs']     = 'programs';
$routes['/programs/:id'] = 'program';

$routes['/products/:productID/issues'] = 'productIssues';
$routes['/projects/:projectID/issues'] = 'issues';
$routes['/issues']                     = 'issues';
$routes['/issues/:issueID']            = 'issue';

$routes['/todos']              = 'todos';
$routes['/todos/:id']          = 'todo';
$routes['/todos/:id/finish']   = 'todoFinish';
$routes['/todos/:id/activate'] = 'todoActivate';

$routes['/projects/:projectID/builds'] = 'builds';
$routes['/builds']                     = 'builds';
$routes['/builds/:id']                 = 'build';

$routes['/products/:id/testcases'] = 'testcases';
$routes['/testcases']              = 'testcases';
$routes['/testcases/:id']          = 'testcase';

$routes['/projects/:projectID/testtasks'] = 'testtasks';
$routes['/testtasks']                     = 'testtasks';
$routes['/testtasks/:id']                 = 'testtask';

$routes['/projects/:projectID/risks'] = 'risks';
$routes['/risks']                     = 'risks';
$routes['/risks/:id']                 = 'risk';

$routes['/departments']     = 'departments';
$routes['/departments/:id'] = 'department';

$routes['/z/folders']           = 'zfolders';
$routes['/z/folders/:id']       = 'zfolder';
$routes['/z/files/:id']         = 'zfile';
$routes['/z/files/:id/content'] = 'zfileContent';

$config->routes = $routes;
