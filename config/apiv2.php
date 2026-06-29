<?php
$routes = array();

$routes['/programs']                     = array('response' => 'programs(array),pager', 'search' => array('enabled' => true));
$routes['/programs/:programID']          = array('redirect' => '/programs/:programID/edit', 'response' => 'program');
$routes['/programs/:programID/projects'] = array('redirect' => '/programs/:programID/project', 'response' => 'projectStats(array)|projects,pager');
$routes['/programs/:programID/products'] = array('redirect' => '/programs/:programID/product', 'response' => 'products(array),pager');

$routes['/products']            = array('redirect' => '/products/all');
$routes['/products/all']        = array('response' => 'productStats|products,pager');
$routes['/products/browse']     = array('response' => 'stories,pager', 'response' => 'stories(array),pager'); // stories
$routes['/products/:productID'] = array('response' => 'product,dynamics,members,branches,reviewers');

$routes['/products/:productID/stories']     = array('redirect' => '/products/browse?productID=:productID', 'search' => array('enabled' => true));
$routes['/projects/:projectID/stories']     = array('redirect' => '/projectstories/story?projectID=:projectID', 'response' => 'stories(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'projectstory', 'querySessionKey' => 'projectstory'));
$routes['/executions/:executionID/stories'] = array('redirect' => '/executions/story?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'executionStory', 'querySessionKey' => 'executionStory'));
$routes['/stories/:storyID']                = array('response' => 'story,actions(array)');

$routes['/products/:productID/epics']     = array('redirect' => '/products/browse?productID=:productID&storyType=epic', 'response' => 'stories(array)|epics,pager', 'search' => array('enabled' => true));
$routes['/epics/:storyID']                = array('response' => 'story|epic,actions(array)');

$routes['/products/:productID/requirements'] = array('redirect' => '/products/browse?productID=:productID&storyType=requirement', 'response' => 'stories(array)|requirements,pager', 'search' => array('enabled' => true));
$routes['/requirements/:storyID']            = array('response' => 'story|requirement,actions(array)');

$routes['/products/:productID/productplans'] = array('redirect' => '/productplans?productID=:productID', 'response' => 'plans(array)|productplans,pager', 'search' => array('enabled' => true));
$routes['/productplans/:planID']             = array('get' => array ('response' => 'plan|productplan,actions(array)'), 'put' => array ('response' => '*'));

$routes['/products/:productID/releases'] = array('redirect' => '/releases?productID=:productID', 'response' => 'releases,pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/releases'] = array('redirect' => '/projectreleases?projectID=:projectID', 'response' => 'releases,pager', 'search' => array('enabled' => true, 'searchModule' => 'release'));
$routes['/releases/:releaseID']          = array('response' => 'release,actions(array)');

$routes['/projects']                    = array('response' => 'projectStats|projects,pager', 'search' => array('enabled' => true));
$routes['/projects/list/:browseType']   = array('redirect' => '/projects?browseType=:browseType');
$routes['/projects/execution']          = array('response' => 'executionStats|executions,pager');
$routes['/projects/build']              = array('response' => 'builds,pager', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'projectBuild'));
$routes['/projects/bug']                = array('response' => 'bugs,pager', 'response' => 'bugs(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'bug', 'querySessionKey' => 'projectBug'));
$routes['/projects/testcase']           = array('response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'testcase'));
$routes['/projects/testtask']           = array('response' => 'tasks(array)|testtasks,pager');
$routes['/projects/testreport']         = array('response' => 'reports(array)|testreports,pager');
$routes['/projects/team']               = array('response' => 'teamMembers(array)|members');
$routes['/projects/:projectID/members'] = array('get' => array('redirect' => '/projects/team?projectID=:projectID'), 'put' => array('redirect' => '/projects/manageMembers?projectID=:projectID'));
$routes['/projects/:projectID']         = array('response' => 'project');

$routes['/executions']                     = array('method' => 'all', 'response' => 'executionStats|executions,pager', 'search' => array('enabled' => true, 'searchModule' => 'execution', 'querySessionKey' => 'execution'));
$routes['/projects/:projectID/executions'] = array('redirect' => '/projects/execution?projectID=:projectID');
$routes['/executions/task']                = array('response' => 'tasks(array),pager');
$routes['/executions/story']               = array('response' => 'stories(array),pager');
$routes['/executions/build']               = array('response' => 'builds,pager', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'executionBuild'));
$routes['/executions/bug']                 = array('response' => 'bugs(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'bug', 'querySessionKey' => 'executionBug'));
$routes['/executions/testcase']            = array('response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'executionCase'));
$routes['/executions/testtask']            = array('response' => 'tasks(array)|testtasks,pager');
$routes['/executions/testreport']          = array('response' => 'reports(array)|testreports,pager');
$routes['/executions/:executionID']        = array('response' => 'execution');

$routes['/executions/:executionID/tasks'] = array('redirect' => '/executions/task?executionID=:executionID');
$routes['/tasks/:taskID']                 = array('response' => 'task,actions(array)');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'projectBuild'));
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'executionBuild'));
$routes['/builds/:buildID']                = array('response' => 'build,actions(array)');

$routes['/products/:productID/bugs']     = array('redirect' => '/bugs?productID=:productID', 'response' => 'bugs(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/bugs']     = array('redirect' => '/projects/bug?projectID=:projectID', 'search' => array('enabled' => true, 'searchModule' => 'projectBug', 'querySessionKey' => 'projectBug'));
$routes['/executions/:executionID/bugs'] = array('redirect' => '/executions/bug?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'executionBug', 'querySessionKey' => 'executionBug'));
$routes['/bugs/:bugID']                  = array('response' => 'bug,actions(array)');

$routes['/products/:productID/testcases']     = array('redirect' => '/testcases?productID=:productID', 'response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'testcase'));
$routes['/projects/:projectID/testcases']     = array('redirect' => '/projects/testcase?projectID=:projectID', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'testcase'));
$routes['/executions/:executionID/testcases'] = array('redirect' => '/executions/testcase?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'executionCase'));
$routes['/testcases/:caseID']                 = array('get' => array('response' => 'testcase,actions(array)'), 'put' => array());

$routes['/products/:productID/testtasks']     = array('redirect' => '/testtasks?productID=:productID', 'response' => 'tasks(array)|testtasks,pager');
$routes['/projects/:projectID/testtasks']     = array('redirect' => '/projects/testtask?projectID=:projectID');
$routes['/executions/:executionID/testtasks'] = array('redirect' => '/executions/testtask?executionID=:executionID');
$routes['/testtasks/:testtaskID']             = array('response' => 'task|testtask,actions(array)');

$routes['/products/:productID/testreports']     = array('redirect' => '/testreports?objectID=:productID', 'response' => 'reports(array)|testreports,pager');
$routes['/projects/:projectID/testreports']     = array('redirect' => '/projects/testreport?projectID=:projectID');
$routes['/executions/:executionID/testreports'] = array('redirect' => '/executions/testreport?executionID=:executionID');
$routes['/testreports/:reportID']               = array('response' => 'report|testreport,actions(array)');

$routes['/issues']                         = array('response' => 'issueList(array)|issues,pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/issues']     = array('redirect' => '/issues?objectID=:projectID', 'search' => array('enabled' => true));
$routes['/executions/:executionID/issues'] = array('redirect' => '/issues?objectID=:executionID&from=execution', 'search' => array('enabled' => true));
$routes['/issues/:issueID']                = array('response' => 'issue,actions(array)');

$routes['/risks']                         = array('response' => 'risks(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/risks']     = array('redirect' => '/risks?projectID=:projectID', 'search' => array('enabled' => true));
$routes['/executions/:executionID/risks'] = array('redirect' => '/risks?executionID=:executionID&from=execution', 'search' => array('enabled' => true));
$routes['/risks/:riskID']                 = array('response' => 'risk,actions(array)');

$routes['/opportunities']                         = array('response' => 'opportunities(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/opportunities']     = array('redirect' => '/opportunities?projectID=:projectID', 'search' => array('enabled' => true));
$routes['/executions/:executionID/opportunities'] = array('redirect' => '/opportunities?executionID=:executionID&from=execution', 'search' => array('enabled' => true));
$routes['/opportunities/:opportunityID']          = array('response' => 'opportunity,actions(array)');

$routes['/auditplans']                          = array('response' => 'auditplans(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/auditplans']      = array('redirect' => '/auditplans?projectID=:projectID', 'search' => array('enabled' => true));
$routes['/executions/:executionID/auditplans']  = array('redirect' => '/auditplans?executionID=:executionID&from=execution', 'search' => array('enabled' => true));

$routes['/feedbacks']                     = array('method' => 'admin', 'response' => 'feedbacks(array),pager', 'search' => array('enabled' => true));
$routes['/products/:productID/feedbacks'] = array('redirect' => '/feedbacks?param=:productID', 'search' => array('enabled' => true));
$routes['/feedbacks/:feedbackID']         = array('response' => 'feedback,actions(array)');

$routes['/tickets']                     = array('response' => 'tickets(array),pager', 'search' => array('enabled' => true));
$routes['/products/:productID/tickets'] = array('redirect' => '/tickets?param=:productID', 'search' => array('enabled' => true));
$routes['/tickets/:ticketID']           = array('response' => 'ticket,actions(array)');

$routes['/systems']                     = array('response' => 'appList(array)|systems,pager');
$routes['/products/:productID/systems'] = array('redirect' => '/systems?productID=:productID');
$routes['/systems/:systemID']           = array('response' => 'system,actions(array)');

$routes['/todos/my']      = array('redirect' => '/my/todo', 'response' => 'todos(array),pager');
$routes['/todos/:todoID'] = array('response' => 'todo');

$routes['/my/todos']        = array('redirect' => '/my/todo',                   'response' => 'todos(array),pager');
$routes['/my/tasks']        = array('redirect' => '/my/task',                   'rawMethod' => 'work', 'mode' => 'task', 'response' => 'tasks(array),pager',         'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workTask'));
$routes['/my/bugs']         = array('redirect' => '/my/work?mode=bug',          'response' => 'bugs(array),pager',          'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workBug'));
$routes['/my/stories']      = array('redirect' => '/my/work?mode=story',        'response' => 'stories(array),pager',       'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workStory'));
$routes['/my/epics']        = array('redirect' => '/my/work?mode=epic',         'response' => 'stories(array)|epics,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workEpic'));
$routes['/my/requirements'] = array('redirect' => '/my/work?mode=requirement',  'response' => 'stories(array)|requirements,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workRequirement'));
$routes['/my/testtasks']    = array('redirect' => '/my/work?mode=testtask',     'response' => 'tasks(array)|testtasks,pager');
$routes['/my/testcases']    = array('redirect' => '/my/work?mode=testcase',     'response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workTestcase'));
$routes['/my/projects']     = array('redirect' => '/my/project',                'response' => 'projects(array),pager');
$routes['/my/executions']   = array('redirect' => '/my/execution',              'response' => 'executions(array),pager');
$routes['/my/issues']       = array('redirect' => '/my/work?mode=issue',        'response' => 'issues(array),pager');
$routes['/my/risks']        = array('redirect' => '/my/work?mode=risk',         'response' => 'risks(array),pager',          'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workRisk'));
$routes['/my/reviewissues'] = array('redirect' => '/my/work?mode=reviewissue',  'response' => 'reviewissues(array),pager',   'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workReviewissue'));
$routes['/my/audits']       = array('redirect' => '/my/audit',                  'response' => 'reviewList(array)|audits,pager');
$routes['/my/auditplans']   = array('redirect' => '/my/auditplan',              'response' => 'auditplans(array),pager');
$routes['/my/ncs']          = array('redirect' => '/my/nc',                     'response' => 'ncs(array),pager');
$routes['/my/meetings']     = array('redirect' => '/my/work?mode=mymeeting',    'response' => 'meetings(array),pager');
$routes['/my/feedbacks']    = array('redirect' => '/my/work?mode=feedback',     'response' => 'feedbacks(array),pager',      'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workFeedback'));
$routes['/my/tickets']      = array('redirect' => '/my/work?mode=ticket',       'response' => 'tickets(array),pager',        'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workTicket'));

$routes['/my/activity/tasks']        = array('redirect' => '/my/contribute?mode=task',        'response' => 'tasks(array),pager',         'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeTask'));
$routes['/my/activity/bugs']         = array('redirect' => '/my/contribute?mode=bug',         'response' => 'bugs(array),pager',          'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeBug'));
$routes['/my/activity/stories']      = array('redirect' => '/my/contribute?mode=story',       'response' => 'stories(array),pager',       'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeStory'));
$routes['/my/activity/epics']        = array('redirect' => '/my/contribute?mode=epic',        'response' => 'stories(array)|epics,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeEpic'));
$routes['/my/activity/requirements'] = array('redirect' => '/my/contribute?mode=requirement', 'response' => 'stories(array)|requirements,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeRequirement'));
$routes['/my/activity/testtasks']    = array('redirect' => '/my/contribute?mode=testtask',    'response' => 'tasks(array)|testtasks,pager');
$routes['/my/activity/testcases']    = array('redirect' => '/my/contribute?mode=testcase',    'response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeTestcase'));
$routes['/my/activity/docs']         = array('redirect' => '/my/contribute?mode=doc',         'response' => 'docs(array),pager');
$routes['/my/activity/issues']       = array('redirect' => '/my/contribute?mode=issue',       'response' => 'issues(array),pager');
$routes['/my/activity/risks']        = array('redirect' => '/my/contribute?mode=risk',        'response' => 'risks(array),pager',          'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeRisk'));
$routes['/my/activity/reviewissues'] = array('redirect' => '/my/contribute?mode=reviewissue', 'response' => 'reviewissues(array),pager',   'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeReviewissue'));
$routes['/my/activity/audits']       = array('redirect' => '/my/contribute?mode=audit',       'response' => 'reviewList(array)|audits,pager');
$routes['/my/activity/feedbacks']    = array('redirect' => '/my/contribute?mode=feedback',    'response' => 'feedbacks(array),pager',      'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'contributeFeedback'));

$routes['/doc/my/spaces'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=mine&picks=space'),
    'post' => array('redirect' => '/doc/createSpace?type=mine', 'data' => 'type=mine')
);
$routes['/doc/team/spaces'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=custom&picks=space'),
    'post' => array('redirect' => '/doc/createSpace?type=custom', 'data' => 'type=custom')
);
$routes['/doc/product/spaces'] = array('redirect' => '/doc/ajaxGetSpaceData?type=product&picks=space');
$routes['/doc/project/spaces'] = array('redirect' => '/doc/ajaxGetSpaceData?type=project&picks=space');

$routes['/doc/spaces/:spaceID'] = array(
    'get'    => array('redirect' => '/doc/editSpace?spaceID=:spaceID', 'response' => 'lib|space'),
    'put'    => array('redirect' => '/doc/editSpace?spaceID=:spaceID'),
    'delete' => array('redirect' => '/doc/deleteSpace?libID=:spaceID')
);

$routes['/doc/my/spaces/:spaceID/libs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=mine&spaceID=:spaceID&picks=lib'),
    'post' => array('redirect' => '/doc/createLib?type=mine&objectID=:spaceID&libID=0', 'data' => 'type=mine&parent=:spaceID')
);
$routes['/doc/team/spaces/:spaceID/libs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=custom&spaceID=:spaceID&picks=lib'),
    'post' => array('redirect' => '/doc/createLib?type=custom&objectID=:spaceID&libID=0', 'data' => 'type=custom&parent=:spaceID')
);
$routes['/doc/product/spaces/:productID/libs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=product&spaceID=:productID&picks=lib'),
    'post' => array('redirect' => '/doc/createLib?type=product&objectID=:productID&libID=0', 'data' => 'type=product&product=:productID')
);
$routes['/doc/project/spaces/:projectID/libs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=project&spaceID=:projectID&picks=lib'),
    'post' => array('redirect' => '/doc/createLib?type=project&objectID=:projectID&libID=0', 'data' => 'type=project&project=:projectID')
);
$routes['/doc/libs/:libID'] = array(
    'get'    => array('redirect' => '/doc/editLib?libID=:libID', 'response' => 'lib'),
    'put'    => array('redirect' => '/doc/editLib?libID=:libID'),
    'delete' => array('redirect' => '/doc/deleteLib?libID=:libID')
);
$routes['/doc/my/spaces/:spaceID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=mine&spaceID=:spaceID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=mine&objectID=:spaceID&libID=:libID&moduleID=0&docType=')
);
$routes['/doc/team/spaces/:spaceID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=custom&spaceID=:spaceID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=custom&objectID=:spaceID&libID=:libID&moduleID=0&docType=')
);
$routes['/doc/product/spaces/:productID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=product&spaceID=:productID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=product&objectID=:productID&libID=:libID&moduleID=0&docType=')
);
$routes['/doc/project/spaces/:projectID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=project&spaceID=:projectID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=project&objectID=:projectID&libID=:libID&moduleID=0&docType=')
);
$routes['/doc/docs/:docID'] = array(
    'get'    => array('redirect' => '/doc/ajaxGetDoc?docID=:docID'),
    'put'    => array('redirect' => '/doc/edit?docID=:docID'),
    'delete' => array('redirect' => '/doc/delete?docID=:docID')
);
$routes['/doc/docs/:docID/collect'] = array(
    'post' => array('redirect' => '/doc/collect?objectID=:docID')
);

$routes['/dynamics/date/:timestamp'] = array('redirect' => '/companies/dynamic?browseType=date&date=:timestamp&limit=100000', 'response' => 'dateGroups|actions');
$routes['/dynamics/product/:productID'] = array('redirect' => '/companies/dynamic?browseType=all&productID=:productID&limit=100000', 'response' => 'dateGroups|actions');
$routes['/dynamics/project/:projectID'] = array('redirect' => '/companies/dynamic?browseType=all&projectID=:projectID&limit=100000', 'response' => 'dateGroups|actions');
$routes['/dynamics/execution/:executionID'] = array('redirect' => '/companies/dynamic?browseType=all&executionID=:executionID&limit=100000', 'response' => 'dateGroups|actions');
$routes['/dynamics/user/:userID'] = array('redirect' => '/companies/dynamic?browseType=all&userID=:userID&limit=10000', 'response' => 'dateGroups|actions');

$routes['/depts']         = array('response' => 'sons|depts');
$routes['/depts/browse']  = array();
$routes['/depts/:deptID'] = array('redirect' => '/depts/browse?deptID=:deptID', 'response' => 'sons');

$routes['/users']         = array('redirect' => '/companies/browse', 'response' => 'users,pager', 'search' => array('enabled' => true, 'searchModule' => 'user', 'querySessionKey' => 'user'));
$routes['/users/:userID'] = array('redirect' => '/users/:userID/profile', 'response' => 'user');

$routes['/files/:fileID'] = array('redirect' => '/files/:fileID/ajaxQuery');
$routes['/files/:fileID/download'] = array('method' => 'download');

/* Modules. */
$routes['/doc/my/spaces/:spaceID/libs/:libID/modules'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=mine&spaceID=:spaceID&libID=:libID&picks=module'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:libID&moduleType=doc')
);
$routes['/doc/team/spaces/:spaceID/libs/:libID/modules'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=custom&spaceID=:spaceID&libID=:libID&picks=module'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:libID&moduleType=doc')
);
$routes['/doc/product/spaces/:productID/libs/:libID/modules'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=product&spaceID=:productID&libID=:libID&picks=module'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:libID&moduleType=doc')
);
$routes['/doc/project/spaces/:projectID/libs/:libID/modules'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=project&spaceID=:projectID&libID=:libID&picks=module'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:libID&moduleType=doc')
);
$routes['/doc/modules/:moduleID'] = array(
    'put'    => array('redirect' => '/tree/edit?moduleID=:moduleID&type=doc'),
    'delete' => array('redirect' => '/tree/delete?moduleID=:moduleID')
);

$routes['/executions/:executionID/task/modules'] = array(
    'get'  => array('redirect' => '/tree/browsetask?rootID=:executionID', 'response' => 'tree'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:executionID&moduleType=task')
);
$routes['/products/:productID/story/modules'] = array(
    'get'  => array('redirect' => '/tree/browse?viewType=story&rootID=:productID', 'response' => 'tree'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:productID&moduleType=story')
);
$routes['/products/:productID/bug/modules'] = array(
    'get'  => array('redirect' => '/tree/browse?viewType=bug&rootID=:productID', 'response' => 'tree'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:productID&moduleType=bug')
);
$routes['/products/:productID/testcase/modules'] = array(
    'get'  => array('redirect' => '/tree/browse?viewType=case&rootID=:productID', 'response' => 'tree'),
    'post' => array('redirect' => '/tree/ajaxCreateModule', 'data' => 'libID=:productID&moduleType=case')
);
$routes['/testcase/modules/:moduleID'] = array(
    'put'    => array('redirect' => '/tree/edit?moduleID=:moduleID&type=case', 'data' => 'moduleID=:moduleID&type=case'),
    'delete' => array('redirect' => '/tree/delete?moduleID=:moduleID')
);
$routes['/:type/modules/:moduleID'] = array(
    'put'    => array('redirect' => '/tree/edit?moduleID=:moduleID&type=:type', 'data' => 'moduleID=:moduleID&type=:type'),
    'delete' => array('redirect' => '/tree/delete?moduleID=:moduleID')
);
