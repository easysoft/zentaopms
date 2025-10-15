<?php
$routes = array();

$routes['/programs']                     = array('response' => 'programs(array),pager');
$routes['/programs/:programID/projects'] = array('redirect' => '/programs/project?programID=:programID', 'response' => 'projectStats|projects,pager');
$routes['/programs/:programID/products'] = array('redirect' => '/programs/product?programID=:programID', 'response' => 'products,pager');

$routes['/products']            = array('redirect' => '/products/all');
$routes['/products/all']        = array('response' => 'productStats|products,pager');
$routes['/products/browse']     = array('response' => 'stories,pager', 'response' => 'stories(array),pager'); // stories
$routes['/products/:productID'] = array('response' => 'product,dynamics,members,branches,reviewers');

$routes['/products/:productID/stories']     = array('redirect' => '/products/browse?productID=:productID');
$routes['/projects/:projectID/stories']     = array('redirect' => '/projectstories/story?projectID=:projectID', 'response' => 'stories(array),pager');
$routes['/executions/:executionID/stories'] = array('redirect' => '/executions/story?executionID=:executionID');
$routes['/stories/:storyID']                = array('response' => 'story,actions');

$routes['/products/:productID/productplans'] = array('redirect' => '/productplans?productID=:productID', 'response' => 'plans(array)|productplans,pager');
$routes['/productplans/:planID']      = array('response' => 'plan|productplan,actions');

$routes['/products/:productID/releases'] = array('redirect' => '/releases?productID=:productID', 'response' => 'releases,pager');
$routes['/projects/:projectID/releases'] = array('redirect' => '/projectreleases?projectID=:projectID', 'response' => 'releases,pager');
$routes['/releases/:releaseID']          = array('response' => 'release,actions');

$routes['/projects']                  = array('response' => 'projectStats|projects,pager');
$routes['/projects/list/:browseType'] = array('redirect' => '/projects?browseType=:browseType');
$routes['/projects/execution']        = array('response' => 'executionStats|executions,pager');
$routes['/projects/build']            = array('response' => 'builds,pager');
$routes['/projects/bug']              = array('response' => 'bugs,pager', 'response' => 'bugs(array),pager');
$routes['/projects/testcase']         = array('response' => 'cases(array)|testcases,pager');
$routes['/projects/testtask']         = array('response' => 'tasks(array)|testtasks,pager');
$routes['/projects/testreport']       = array('response' => 'reports(array)|testreports,pager');
$routes['/projects/:projectID']       = array('response' => 'project');

$routes['/projects/:projectID/executions'] = array('redirect' => '/projects/execution?projectID=:projectID');
$routes['/executions/task']                = array('response' => 'tasks,pager', 'response' => 'tasks(array),pager');
$routes['/executions/story']               = array('response' => 'stories,pager', 'response' => 'stories(array),pager');
$routes['/executions/build']               = array('response' => 'builds,pager');
$routes['/executions/bug']                 = array('response' => 'bugs,pager', 'response' => 'bugs(array),pager');
$routes['/executions/testcase']            = array('response' => 'cases(array)|testcases,pager');
$routes['/executions/testtask']            = array('response' => 'tasks(array)|testtasks,pager');
$routes['/executions/testreport']          = array('response' => 'reports(array)|testreports,pager');
$routes['/executions/:executionID']        = array('response' => 'execution');

$routes['/executions/:executionID/tasks'] = array('redirect' => '/executions/task?executionID=:executionID');
$routes['/tasks/:taskID']                 = array('response' => 'task,actions');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID');
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID');
$routes['/builds/:buildID']                = array('response' => 'build,actions');

$routes['/products/:productID/bugs']     = array('redirect' => '/bugs?productID=:productID', 'response' => 'bugs(array),pager');
$routes['/projects/:projectID/bugs']     = array('redirect' => '/projects/bug?projectID=:projectID');
$routes['/executions/:executionID/bugs'] = array('redirect' => '/executions/bug?executionID=:executionID');
$routes['/bugs/:bugID']                  = array('response' => 'bug,actions');

$routes['/products/:productID/testcases']     = array('redirect' => '/testcases?productID=:productID', 'response' => 'cases(array)|testcases,pager');
$routes['/projects/:projectID/testcases']     = array('redirect' => '/projects/testcase?projectID=:projectID');
$routes['/executions/:executionID/testcases'] = array('redirect' => '/executions/testcase?executionID=:executionID');
$routes['/testcases/:caseID']                 = array('response' => 'testcase,actions');

$routes['/products/:productID/testtasks']     = array('redirect' => '/testtasks?productID=:productID', 'response' => 'tasks(array)|testtasks,pager');
$routes['/projects/:projectID/testtasks']     = array('redirect' => '/projects/testtask?projectID=:projectID');
$routes['/executions/:executionID/testtasks'] = array('redirect' => '/executions/testtask?executionID=:executionID');
$routes['/testtasks/:testtaskID']             = array('response' => 'task|testtask,actions');

$routes['/products/:productID/testreports']     = array('redirect' => '/testreports?productID=:productID', 'response' => 'reports(array)|testreports,pager');
$routes['/projects/:projectID/testreports']     = array('redirect' => '/projects/testreport?projectID=:projectID');
$routes['/executions/:executionID/testreports'] = array('redirect' => '/executions/testreport?executionID=:executionID');
$routes['/testreports/:reportID']               = array('response' => 'report|testreport,actions');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID');
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID');
$routes['/builds/:buildID']                = array('response' => 'build,actions');

$routes['/depts']         = array('response' => 'sons|depts');
$routes['/depts/browse']  = array();
$routes['/depts/:deptID'] = array('redirect' => '/depts/browse?deptID=:deptID', 'response' => 'sons');

$routes['/users']         = array('redirect' => '/companies/browse', 'response' => 'users,pager');
$routes['/users/:userID'] = array('redirect' => '/users/profile', 'response' => 'user');
