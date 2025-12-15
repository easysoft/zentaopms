<?php
$routes = array();

$routes['/programs']                     = array('response' => 'programs(array),pager');
$routes['/programs/:programID']          = array('redirect' => '/programs/:programID/edit', 'response' => 'program');
$routes['/programs/:programID/projects'] = array('redirect' => '/programs/:programID/project', 'response' => 'projectStats(array)|projects,pager');
$routes['/programs/:programID/products'] = array('redirect' => '/programs/:programID/product', 'response' => 'products(array),pager');

$routes['/products']            = array('redirect' => '/products/all');
$routes['/products/all']        = array('response' => 'productStats|products,pager');
$routes['/products/browse']     = array('response' => 'stories,pager', 'response' => 'stories(array),pager'); // stories
$routes['/products/:productID'] = array('response' => 'product,dynamics,members,branches,reviewers');

$routes['/products/:productID/stories']     = array('redirect' => '/products/browse?productID=:productID');
$routes['/projects/:projectID/stories']     = array('redirect' => '/projectstories/story?projectID=:projectID', 'response' => 'stories(array),pager');
$routes['/executions/:executionID/stories'] = array('redirect' => '/executions/story?executionID=:executionID');
$routes['/stories/:storyID']                = array('response' => 'story,actions(array)');

$routes['/products/:productID/productplans'] = array('redirect' => '/productplans?productID=:productID', 'response' => 'plans(array)|productplans,pager');
$routes['/productplans/:planID']      = array('response' => 'plan|productplan,actions(array)');

$routes['/products/:productID/releases'] = array('redirect' => '/releases?productID=:productID', 'response' => 'releases,pager');
$routes['/projects/:projectID/releases'] = array('redirect' => '/projectreleases?projectID=:projectID', 'response' => 'releases,pager');
$routes['/releases/:releaseID']          = array('response' => 'release,actions(array)');

$routes['/projects']                  = array('response' => 'projectStats|projects,pager');
$routes['/projects/list/:browseType'] = array('redirect' => '/projects?browseType=:browseType');
$routes['/projects/execution']        = array('response' => 'executionStats|executions,pager');
$routes['/projects/build']            = array('response' => 'builds,pager');
$routes['/projects/bug']              = array('response' => 'bugs,pager', 'response' => 'bugs(array),pager');
$routes['/projects/testcase']         = array('response' => 'cases(array)|testcases,pager');
$routes['/projects/testtask']         = array('response' => 'tasks(array)|testtasks,pager');
$routes['/projects/testreport']       = array('response' => 'reports(array)|testreports,pager');
$routes['/projects/:projectID']       = array('response' => 'project');

$routes['/executions']                     = array('method' => 'all', 'response' => 'executionStats|executions,pager');
$routes['/projects/:projectID/executions'] = array('redirect' => '/projects/execution?projectID=:projectID');
$routes['/executions/task']                = array('response' => 'tasks(array),pager');
$routes['/executions/story']               = array('response' => 'stories(array),pager');
$routes['/executions/build']               = array('response' => 'builds,pager');
$routes['/executions/bug']                 = array('response' => 'bugs(array),pager');
$routes['/executions/testcase']            = array('response' => 'cases(array)|testcases,pager');
$routes['/executions/testtask']            = array('response' => 'tasks(array)|testtasks,pager');
$routes['/executions/testreport']          = array('response' => 'reports(array)|testreports,pager');
$routes['/executions/:executionID']        = array('response' => 'execution');

$routes['/executions/:executionID/tasks'] = array('redirect' => '/executions/task?executionID=:executionID');
$routes['/tasks/:taskID']                 = array('response' => 'task,actions(array)');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID');
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID');
$routes['/builds/:buildID']                = array('response' => 'build,actions(array)');

$routes['/products/:productID/bugs']     = array('redirect' => '/bugs?productID=:productID', 'response' => 'bugs(array),pager');
$routes['/projects/:projectID/bugs']     = array('redirect' => '/projects/bug?projectID=:projectID');
$routes['/executions/:executionID/bugs'] = array('redirect' => '/executions/bug?executionID=:executionID');
$routes['/bugs/:bugID']                  = array('response' => 'bug,actions(array)');

$routes['/products/:productID/testcases']     = array('redirect' => '/testcases?productID=:productID', 'response' => 'cases(array)|testcases,pager');
$routes['/projects/:projectID/testcases']     = array('redirect' => '/projects/testcase?projectID=:projectID');
$routes['/executions/:executionID/testcases'] = array('redirect' => '/executions/testcase?executionID=:executionID');
$routes['/testcases/:caseID']                 = array('response' => 'testcase,actions(array)');

$routes['/products/:productID/testtasks']     = array('redirect' => '/testtasks?productID=:productID', 'response' => 'tasks(array)|testtasks,pager');
$routes['/projects/:projectID/testtasks']     = array('redirect' => '/projects/testtask?projectID=:projectID');
$routes['/executions/:executionID/testtasks'] = array('redirect' => '/executions/testtask?executionID=:executionID');
$routes['/testtasks/:testtaskID']             = array('response' => 'task|testtask,actions(array)');

$routes['/products/:productID/testreports']     = array('redirect' => '/testreports?productID=:productID', 'response' => 'reports(array)|testreports,pager');
$routes['/projects/:projectID/testreports']     = array('redirect' => '/projects/testreport?projectID=:projectID');
$routes['/executions/:executionID/testreports'] = array('redirect' => '/executions/testreport?executionID=:executionID');
$routes['/testreports/:reportID']               = array('response' => 'report|testreport,actions(array)');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID');
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID');
$routes['/builds/:buildID']                = array('response' => 'build,actions(array)');

$routes['/issues']                         = array('response' => 'issueList(array)|issues,pager');
$routes['/projects/:projectID/issues']     = array('redirect' => '/issues?objectID=:projectID');
$routes['/executions/:executionID/issues'] = array('redirect' => '/issues?objectID=:executionID&from=execution');
$routes['/issues/:issueID']                = array('response' => 'issue,actions(array)');

$routes['/risks']                         = array('response' => 'risks(array),pager');
$routes['/projects/:projectID/risks']     = array('redirect' => '/risks?projectID=:projectID');
$routes['/executions/:executionID/risks'] = array('redirect' => '/risks?executionID=:executionID&from=execution');
$routes['/risks/:riskID']                 = array('response' => 'risk,actions(array)');

$routes['/opportunities']                         = array('response' => 'opportunities(array),pager');
$routes['/projects/:projectID/opportunities']     = array('redirect' => '/opportunities?projectID=:projectID');
$routes['/executions/:executionID/opportunities'] = array('redirect' => '/opportunities?executionID=:executionID&from=execution');
$routes['/opportunities/:opportunityID']          = array('response' => 'opportunity,actions(array)');

$routes['/auditplans']                          = array('response' => 'auditplans(array),pager');
$routes['/projects/:projectID/auditplans']      = array('redirect' => '/auditplans?projectID=:projectID');
$routes['/executions/:executionID/auditplans']  = array('redirect' => '/auditplans?executionID=:executionID&from=execution');

$routes['/feedbacks']                     = array('response' => 'feedbacks(array),pager');
$routes['/products/:productID/feedbacks'] = array('redirect' => '/feedbacks?productID=:productID');
$routes['/feedbacks/:feedbackID']         = array('response' => 'feedback,actions(array)');

$routes['/tickets']                     = array('response' => 'tickets(array),pager');
$routes['/products/:productID/tickets'] = array('redirect' => '/tickets?browseType=byProduct&param=:productID');
$routes['/tickets/:ticketID']           = array('response' => 'ticket,actions(array)');

$routes['/depts']         = array('response' => 'sons|depts');
$routes['/depts/browse']  = array();
$routes['/depts/:deptID'] = array('redirect' => '/depts/browse?deptID=:deptID', 'response' => 'sons');

$routes['/users']         = array('redirect' => '/companies/browse', 'response' => 'users,pager');
$routes['/users/:userID'] = array('redirect' => '/users/profile', 'response' => 'user');
