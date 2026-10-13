<?php
$routes = array();

$routes['/workflowgroups'] = array('redirect' => '/workflowgroups/project', 'response' => 'groups(array),pager');

$routes['/workflows'] = array(
    'get'  => array('redirect' => '/workflows/browseFlow', 'response' => 'flows(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'workflow', 'querySessionKey' => 'workflow')),
    'post' => array('redirect' => '/workflows/create?type=flow', 'data' => 'type=flow')
);
$routes['/workflows/:module/tables'] = array(
    'get'  => array('redirect' => '/workflows/browseDB?parent=:module', 'response' => 'tables(array)'),
    'post' => array('redirect' => '/workflows/create?type=table&parent=:module', 'data' => 'type=table&parent=:module')
);
$routes['/workflows/:flowID'] = array(
    'get' => array('response' => 'flow'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);
$routes['/workflows/:flowID/copy']              = array('post' => array('response' => '*'));
$routes['/workflows/:flowID/release']           = array('put' => array('response' => '*'));
$routes['/workflows/:flowID/deactivate']        = array('put' => array('response' => '*'));
$routes['/workflows/:flowID/activate']          = array('put' => array('response' => '*'));
$routes['/workflows/:flowID/setJS']             = array('put' => array('response' => '*'));
$routes['/workflows/:flowID/setCSS']            = array('put' => array('response' => '*'));
$routes['/workflows/:flowID/setFulltextSearch'] = array('put' => array('response' => '*'));
$routes['/workflows/:module/setApproval']       = array('put' => array('redirect' => '/workflow/setApproval?module=:module', 'response' => '*'));
$routes['/workflows/:module/setValue']          = array('put' => array('redirect' => '/workflowfields/setValue?module=:module', 'response' => '*'));
$routes['/workflows/:module/setExport']         = array('put' => array('redirect' => '/workflowfields/setExport?module=:module', 'response' => '*'));
$routes['/workflows/:module/setSearch']         = array('put' => array('redirect' => '/workflowfields/setSearch?module=:module', 'response' => '*'));
$routes['/workflows/:module/fields'] = array(
    'get'  => array('redirect' => '/workflowfields/browse?module=:module', 'response' => 'fields(array)'),
    'post' => array('redirect' => '/workflowfields/create?module=:module')
);
$routes['/workflows/:module/actions'] = array(
    'get'  => array('redirect' => '/workflowactions/browse?module=:module', 'response' => 'actions(array)'),
    'post' => array('redirect' => '/workflowactions/create?module=:module')
);
$routes['/workflows/:module/labels'] = array(
    'get'  => array('redirect' => '/workflowlabels/browse?module=:module', 'response' => 'labels(array)'),
    'post' => array('redirect' => '/workflowlabels/create?module=:module')
);

$routes['/workflowtables/:tableID'] = array(
    'get'    => array('redirect' => '/workflows/:tableID', 'response' => 'flow'),
    'put'    => array('redirect' => '/workflows/:tableID', 'response' => '*'),
    'delete' => array('redirect' => '/workflows/:tableID', 'response' => '*')
);

$routes['/workflowfields/:fieldID'] = array(
    'get' => array('response' => 'field'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);

$routes['/workflowactions/:actionID'] = array(
    'get' => array('response' => 'action'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);
$routes['/workflowactions/:actionID/setVerification'] = array('put' => array('response' => '*'));
$routes['/workflowactions/:actionID/setNotice']       = array('put' => array('response' => '*'));
$routes['/workflowactions/:actionID/setJS']           = array('put' => array('response' => '*'));
$routes['/workflowactions/:actionID/setCSS']          = array('put' => array('response' => '*'));

$routes['/workflowlabels/:labelID'] = array(
    'get' => array('response' => 'label'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);

$routes['/workflowdatasources'] = array(
    'get' => array('response' => 'datasources(array),pager'),
    'post' => array('response' => '*')
);
$routes['/workflowdatasources/:datasourceID'] = array(
    'get' => array('response' => 'datasource'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);

$routes['/workflowrules'] = array(
    'get' => array('response' => 'rules(array),pager'),
    'post' => array('response' => '*')
);
$routes['/workflowrules/:ruleID'] = array(
    'get' => array('response' => 'rule'),
    'put' => array('response' => '*'),
    'delete' => array('response' => '*')
);

$routes['/programs']                     = array('response' => 'programs(array),pager', 'search' => array('enabled' => true));
$routes['/programs/:programID']          = array('redirect' => '/programs/:programID/edit', 'response' => 'program');
$routes['/programs/:programID/projects'] = array('redirect' => '/programs/:programID/project', 'response' => 'projectStats(array)|projects,pager');
$routes['/programs/:programID/products'] = array('redirect' => '/programs/:programID/product', 'response' => 'products(array),pager');

$routes['/products']            = array('redirect' => '/products/all');
$routes['/products/all']        = array('response' => 'productStats|products,pager');
$routes['/products/browse']     = array('response' => 'stories,pager', 'response' => 'stories(array),pager'); // stories
$routes['/products/:productID']       = array('response' => 'product,dynamics,members,branches,reviewers');
$routes['/products/:productID/close'] = array('post' => array('response' => '*'));

$routes['/products/:productID/stories']     = array('redirect' => '/products/browse?productID=:productID', 'search' => array('enabled' => true));
$routes['/projects/:projectID/stories']     = array(
    'get' => array('redirect' => '/projectstories/story?projectID=:projectID', 'response' => 'stories(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'projectstory', 'querySessionKey' => 'projectstory')),
    'post' => array('redirect' => '/story/create?productID=0&branch=0&objectID=:projectID', 'data' => 'project=:projectID')
);
$routes['/projects/:projectID/stories/link']     = array(
    'get'  => array('redirect' => '/execution/linkStory?objectID=:projectID', 'rawModule' => 'projectstory', 'response' => 'allStories(array)|stories,pager,storyType'),
    'post' => array('redirect' => '/execution/linkStory?objectID=:projectID', 'rawModule' => 'projectstory', 'response' => '*')
);
$routes['/executions/:executionID/stories']      = array('redirect' => '/executions/story?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'executionStory', 'querySessionKey' => 'executionStory'));
$routes['/executions/:executionID/stories/link'] = array(
    'get'  => array('redirect' => '/execution/linkStory?objectID=:executionID', 'rawModule' => 'execution', 'response' => 'allStories(array)|stories,pager,storyType'),
    'post' => array('redirect' => '/execution/linkStory?objectID=:executionID', 'rawModule' => 'execution', 'response' => '*')
);
$routes['/stories/:storyID']                     = array('response' => 'story,actions(array)');
$routes['/storygrades']                          = array('redirect' => '/story/ajaxGetGradeList', 'response' => 'grades(array)');

$routes['/products/:productID/epics']     = array('redirect' => '/products/browse?productID=:productID&storyType=epic', 'response' => 'stories(array)|epics,pager', 'search' => array('enabled' => true));
$routes['/epics/:storyID'] = array(
    'get'    => array('response' => 'story|epic,actions(array)'),
    'put'    => array('response' => '*'),
    'delete' => array('response' => '*'),
);
$routes['/epics/:storyID/change']   = array('put' => array('response' => '*'));
$routes['/epics/:storyID/close']    = array('put' => array('response' => '*'));
$routes['/epics/:storyID/activate'] = array('put' => array('response' => '*'));

$routes['/products/:productID/requirements'] = array('redirect' => '/products/browse?productID=:productID&storyType=requirement', 'response' => 'stories(array)|requirements,pager', 'search' => array('enabled' => true));
$routes['/requirements/:storyID'] = array(
    'get'    => array('response' => 'story|requirement,actions(array)'),
    'put'    => array('response' => '*'),
    'delete' => array('response' => '*'),
);
$routes['/requirements/:storyID/change']   = array('put' => array('response' => '*'));
$routes['/requirements/:storyID/close']    = array('put' => array('response' => '*'));
$routes['/requirements/:storyID/activate'] = array('put' => array('response' => '*'));

$routes['/products/:productID/productplans'] = array('redirect' => '/productplans?productID=:productID', 'response' => 'plans(array)|productplans,pager', 'search' => array('enabled' => true));
$routes['/productplans/:planID']             = array('get' => array ('response' => 'plan|productplan,actions(array)'), 'put' => array ('response' => '*'));

$routes['/products/:productID/releases'] = array('redirect' => '/releases?productID=:productID', 'response' => 'releases,pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/releases'] = array('redirect' => '/projectreleases?projectID=:projectID', 'response' => 'releases,pager', 'search' => array('enabled' => true, 'searchModule' => 'release'));
$routes['/releases/:releaseID']          = array('response' => 'release,actions(array)');

$routes['/projects']                    = array('response' => 'projectStats|projects,pager', 'search' => array('enabled' => true));
$routes['/projects/list/:browseType']   = array('redirect' => '/projects?browseType=:browseType');
$routes['/projects/execution']          = array('response' => 'executionStats(array)|executions,pager');
$routes['/projects/build']              = array('response' => 'builds,pager', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'projectBuild'));
$routes['/projects/bug']                = array('response' => 'bugs,pager', 'response' => 'bugs(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'bug', 'querySessionKey' => 'projectBug'));
$routes['/projects/testcase']           = array('response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'testcase'));
$routes['/projects/testtask']           = array('response' => 'tasks(array)|testtasks,pager');
$routes['/projects/testreport']         = array('response' => 'reports(array)|testreports,pager');
$routes['/projects/team']               = array('response' => 'teamMembers(array)|members');
$routes['/projects/:projectID/members'] = array('get' => array('redirect' => '/projects/team?projectID=:projectID'), 'put' => array('redirect' => '/projects/manageMembers?projectID=:projectID'));

$routes['/projects/deliverable']        = array('response' => 'deliverables(array),pager');
$routes['/projects/viewDeliverable']    = array('response' => 'deliverable,review,actions(array),doc,relatedObjects(array)');

$routes['/projects/:projectID']         = array('response' => 'project');
$routes['/projects/:projectID/close']   = array('post' => array('response' => '*'));
$routes['/projects/:projectID/tasks']   = array('post' => array('redirect' => '/task/create?executionID=0', 'data' => 'project=:projectID'));

$routes['/executions']                      = array('method' => 'all', 'response' => 'executionStats(array)|executions,pager', 'search' => array('enabled' => true, 'searchModule' => 'execution', 'querySessionKey' => 'execution'));
$routes['/projects/:projectID/executions']  = array('redirect' => '/projects/execution?projectID=:projectID');
$routes['/executions/task']                 = array('response' => 'tasks(array),pager');
$routes['/executions/story']                = array('response' => 'stories(array),pager');
$routes['/executions/build']                = array('response' => 'builds,pager', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'executionBuild'));
$routes['/executions/bug']                  = array('response' => 'bugs(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'bug', 'querySessionKey' => 'executionBug'));
$routes['/executions/testcase']             = array('response' => 'cases(array)|testcases,pager', 'search' => array('enabled' => true, 'searchModule' => 'testcase', 'querySessionKey' => 'executionCase'));
$routes['/executions/testtask']             = array('response' => 'tasks(array)|testtasks,pager');
$routes['/executions/testreport']           = array('response' => 'reports(array)|testreports,pager');
$routes['/executions/team']                 = array('response' => 'teamMembers(array)|members');
$routes['/executions/:executionID/members'] = array('get' => array('redirect' => '/executions/team?executionID=:executionID'), 'put' => array('redirect' => '/executions/manageMembers?executionID=:executionID'));
$routes['/executions/:executionID']         = array('response' => 'execution');
$routes['/executions/:executionID/close']   = array('post' => array('response' => '*'));

$routes['/executions/:executionID/tasks'] = array('redirect' => '/executions/task?executionID=:executionID');
$routes['/tasks/:taskID']                 = array('response' => 'task,actions(array)');

$routes['/projects/:projectID/builds']     = array('redirect' => '/projects/build?projectID=:projectID', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'projectBuild'));
$routes['/executions/:executionID/builds'] = array('redirect' => '/executions/build?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'build', 'querySessionKey' => 'executionBuild'));
$routes['/builds/:buildID']                = array('response' => 'build,actions(array)');

$routes['/products/:productID/bugs']     = array('redirect' => '/bugs?productID=:productID', 'response' => 'bugs(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/bugs']     = array(
    'get' => array('redirect' => '/projects/bug?projectID=:projectID', 'search' => array('enabled' => true, 'searchModule' => 'projectBug', 'querySessionKey' => 'projectBug')),
    'post' => array('redirect' => '/bug/create?productID=0&branch=0&extras=projectID=:projectID', 'data' => 'project=:projectID')
);
$routes['/executions/:executionID/bugs'] = array('redirect' => '/executions/bug?executionID=:executionID', 'search' => array('enabled' => true, 'searchModule' => 'executionBug', 'querySessionKey' => 'executionBug'));
$routes['/feedbacks/:feedbackID/bugs']   = array('post' => array('redirect' => '/bug/create?productID=0&branch=0&extras=projectID=0,fromType=feedback,fromID=:feedbackID', 'data' => 'feedback=:feedbackID'));
$routes['/bugs/:bugID']                  = array('response' => 'bug,actions(array)');
$routes['/bugs/:bugID/confirm']          = array('put' => array('response' => '*'));

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

$routes['/projects/:projectID/baselines'] = array(
    'get'  => array('redirect' => '/cm/browse?projectID=:projectID', 'response' => 'baselines(array),pager'),
    'post' => array('redirect' => '/cm/create?projectID=:projectID', 'response' => '*')
);
$routes['/baselines/:baselineID']         = array(
    'get'    => array('redirect' => '/cm/view?baselineID=:baselineID', 'response' => 'baseline,actions(array),deliverables(array)'),
    'put'    => array('redirect' => '/cm/edit?baselineID=:baselineID', 'response' => '*'),
    'delete' => array('redirect' => '/cm/delete?baselineID=:baselineID', 'response' => '*')
);

$routes['/projects/:projectID/deliverables'] = array(
    'get'  => array('redirect' => '/projects/deliverable?projectID=:projectID', 'response' => 'deliverables(array),pager'),
    'post' => array('redirect' => '/project/createDeliverable?projectID=:projectID', 'response' => '*')
);
$routes['/deliverables/:deliverableID']      = array(
    'get'    => array('redirect' => '/projects/viewDeliverable?deliverableID=:deliverableID', 'response' => 'deliverable,review,actions(array),doc,relatedObjects(array)'),
    'delete' => array('redirect' => '/project/deleteDeliverable?deliverableID=:deliverableID', 'response' => '*')
);

$routes['/projects/:projectID/changes'] = array(
    'get'  => array('redirect' => '/projectchanges/browse?projectID=:projectID', 'response' => 'projectchanges(array),pager'),
    'post' => array('redirect' => '/projectchanges/create?projectID=:projectID', 'response' => '*')
);
$routes['/changes/:changeID']           = array(
    'get'    => array('redirect' => '/projectchanges/view?projectchangeID=:changeID', 'response' => 'projectchange,actions(array),deliverables(array)'),
    'put'    => array('redirect' => '/projectchanges/edit?projectchangeID=:changeID', 'response' => '*'),
    'delete' => array('redirect' => '/projectchanges/delete?projectchangeID=:changeID', 'response' => '*')
);

$routes['/projects/:projectID/processes'] = array(
    'get'  => array('redirect' => '/pssp/browse?projectID=:projectID', 'response' => 'processList(array)'),
    'post' => array('redirect' => '/pssp/update?projectID=:projectID', 'response' => '*')
);
$routes['/executions/:executionID/processes'] = array(
    'get'  => array('redirect' => '/pssp/browse?projectID=:executionID', 'response' => 'processList(array)'),
    'post' => array('redirect' => '/pssp/update?projectID=:executionID', 'response' => '*')
);

$routes['/weeklies']           = array(
    'get'  => array('response' => 'reports(array),pager'),
    'post' => array('response' => '*')
);
$routes['/weeklies/:reportID'] = array(
    'get'    => array('response' => 'report'),
    'put'    => array('response' => '*'),
    'delete' => array('response' => '*')
);

$routes['/risks']                         = array('get' => array('response' => 'risks(array),pager', 'search' => array('enabled' => true)), 'post' => array('response' => '*'));
$routes['/projects/:projectID/risks']     = array(
    'get'  => array('redirect' => '/risks?projectID=:projectID', 'response' => 'risks(array),pager', 'search' => array('enabled' => true)),
    'post' => array('redirect' => '/risks?projectID=:projectID&from=project', 'response' => '*')
);
$routes['/executions/:executionID/risks'] = array('redirect' => '/risks?executionID=:executionID&from=execution', 'response' => 'risks(array),pager', 'search' => array('enabled' => true));
$routes['/risks/:riskID']                 = array('get' => array('response' => 'risk,actions(array)'), 'put' => array('response' => '*'));

$routes['/meetings']                         = array('get' => array('response' => 'meetings(array),pager', 'search' => array('enabled' => true)), 'post' => array('response' => '*'));
$routes['/projects/:projectID/meetings']     = array('redirect' => '/meetings?objectID=:projectID&from=project', 'response' => 'meetings(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'meeting'));
$routes['/executions/:executionID/meetings'] = array('redirect' => '/meetings?objectID=:executionID&from=execution', 'response' => 'meetings(array),pager', 'search' => array('enabled' => true, 'searchModule' => 'meeting'));
$routes['/meetings/:meetingID']              = array('get' => array('response' => 'meeting,actions(array)'), 'put' => array('response' => '*'), 'delete' => array('response' => '*'));
$routes['/meetings/:meetingID/minutes']      = array('put' => array('method' => 'minutes', 'response' => '*'));

$routes['/opportunities']                         = array('get' => array('response' => 'opportunities(array),pager', 'search' => array('enabled' => true)), 'post' => array('response' => '*'));
$routes['/opportunities/batch']                   = array('post' => array('redirect' => '/opportunities/batchCreate', 'response' => '*'));
$routes['/opportunities/batchCreate']             = array('post' => array('response' => '*'));
$routes['/projects/:projectID/opportunities']     = array(
    'get'  => array('redirect' => '/opportunities?projectID=:projectID', 'response' => 'opportunities(array),pager', 'search' => array('enabled' => true)),
    'post' => array('redirect' => '/opportunities?projectID=:projectID&from=project', 'response' => '*')
);
$routes['/projects/:projectID/opportunities/batch'] = array('post' => array('redirect' => '/opportunities/batchCreate?projectID=:projectID&from=project', 'response' => '*'));
$routes['/executions/:executionID/opportunities']   = array('redirect' => '/opportunities?projectID=:executionID&from=execution', 'response' => 'opportunities(array),pager', 'search' => array('enabled' => true));
$routes['/opportunities/:opportunityID']            = array('get' => array('response' => 'opportunity,actions(array)'), 'put' => array('response' => '*'), 'delete' => array('response' => '*'));

$routes['/auditplans']                          = array(
    'get'  => array('response' => 'auditplans(array),pager', 'search' => array('enabled' => true)),
    'post' => array('response' => '*')
);
$routes['/projects/:projectID/auditplans']       = array('redirect' => '/auditplans?projectID=:projectID', 'response' => 'auditplans(array),pager', 'search' => array('enabled' => true));
$routes['/projects/:projectID/auditplans/batch'] = array('post' => array('redirect' => '/auditplans/batchCreate?projectID=:projectID', 'response' => '*'));
$routes['/executions/:executionID/auditplans']   = array('redirect' => '/auditplans?projectID=:executionID&from=execution', 'response' => 'auditplans(array),pager', 'search' => array('enabled' => true));
$routes['/auditplans/:auditplanID']              = array(
    'get'    => array('response' => 'auditplan,actions(array),results(array),ncs(array)'),
    'put'    => array('response' => '*'),
    'delete' => array('response' => '*')
);
$routes['/auditplans/:auditplanID/check']        = array('post' => array('method' => 'check', 'response' => '*'));
$routes['/projects/:projectID/ncs']              = array(
    'get'  => array('redirect' => '/nc/browse?objectID=:projectID&from=project', 'response' => 'ncs(array),pager'),
    'post' => array('redirect' => '/nc/create?projectID=:projectID', 'response' => '*')
);
$routes['/executions/:executionID/ncs']          = array(
    'get'  => array('redirect' => '/nc/browse?objectID=:executionID&from=execution', 'response' => 'ncs(array),pager'),
    'post' => array('redirect' => '/nc/create?projectID=:executionID&from=execution', 'data' => 'execution=:executionID', 'response' => '*')
);
$routes['/ncs/:ncID']                            = array(
    'get'    => array('redirect' => '/nc/view?ncID=:ncID', 'response' => 'nc,actions(array)'),
    'put'    => array('redirect' => '/nc/:ncID', 'response' => '*'),
    'delete' => array('redirect' => '/nc/:ncID', 'response' => '*')
);

$routes['/feedbacks']                     = array('method' => 'admin', 'response' => 'feedbacks(array),pager', 'search' => array('enabled' => true));
$routes['/products/:productID/feedbacks'] = array('redirect' => '/feedbacks?param=:productID', 'search' => array('enabled' => true));
$routes['/feedbacks/:feedbackID/todos']   = array('post' => array('redirect' => '/todo/create?date=today&from=feedback&param=:feedbackID', 'data' => 'feedback=:feedbackID&type=feedback'));
$routes['/feedbacks/:feedbackID/stories'] = array('post' => array('redirect' => '/story/create?productID=0&branch=0&extra=fromType=feedback,fromID=:feedbackID', 'data' => 'feedback=:feedbackID'));
$routes['/feedbacks/:feedbackID']         = array('response' => 'feedback,actions(array)');

$routes['/tickets']                       = array('response' => 'tickets(array),pager', 'search' => array('enabled' => true));
$routes['/feedbacks/:feedbackID/tickets'] = array('post' => array('redirect' => '/ticket/create?productID=0&extras=fromType=feedback,fromID=:feedbackID', 'data' => 'feedback=:feedbackID'));
$routes['/feedbacks/:feedbackID/tasks']   = array('post' => array('redirect' => '/task/create?executionID=0', 'data' => 'feedback=:feedbackID'));
$routes['/products/:productID/tickets']   = array('redirect' => '/tickets?param=:productID', 'search' => array('enabled' => true));
$routes['/tickets/:ticketID']             = array('response' => 'ticket,actions(array)');
$routes['/tickets/:ticketID/stories']     = array('post' => array('redirect' => '/story/create?productID=0&branch=0&extra=fromType=ticket,fromID=:ticketID', 'data' => 'ticket=:ticketID'));
$routes['/tickets/:ticketID/bugs']        = array('post' => array('redirect' => '/bug/create?productID=0&branch=0&extras=projectID=0,fromType=ticket,fromID=:ticketID', 'data' => 'ticket=:ticketID'));

$routes['/systems']                     = array('response' => 'appList(array)|systems,pager');
$routes['/products/:productID/systems'] = array('redirect' => '/systems?productID=:productID');
$routes['/systems/:systemID']           = array('response' => 'system,actions(array)');

$routes['/relations/:objectType/:objectID']                                                   = array('get' => array('redirect' => '/custom/showRelationGraph?objectID=:objectID&objectType=:objectType', 'response' => 'relations(array)'));
$routes['/relations/:objectType/:objectID/candidates/:relatedObjectType']                     = array('get' => array('redirect' => '/custom/relateObject?objectID=:objectID&objectType=:objectType&relatedObjectType=:relatedObjectType', 'response' => 'objects(array),relationPairs,pager'));
$routes['/relations/:objectType/:objectID/:relatedObjectType']                                = array('post' => array('redirect' => '/custom/relateObject?objectID=:objectID&objectType=:objectType&relatedObjectType=:relatedObjectType', 'response' => '*'));
$routes['/relations/:objectType/:objectID/:relatedObjectType/:relatedObjectID/:relationName'] = array('delete' => array('redirect' => '/custom/removeObjects?objectID=:objectID&objectType=:objectType&relationName=:relationName&relatedObjectID=:relatedObjectID&relatedObjectType=:relatedObjectType', 'response' => '*'));

$routes['/todos/my']      = array('redirect' => '/my/todo', 'response' => 'todos(array),pager');
$routes['/todos/:todoID'] = array('response' => 'todo');

$routes['/my/todos']        = array('redirect' => '/my/todo',                   'response' => 'todos(array),pager');
$routes['/my/tasks']        = array('redirect' => '/my/task',                   'response' => 'tasks(array),pager',         'rawMethod' => 'work', 'mode' => 'task', 'search' => array('enabled' => true, 'searchModule' => 'my', 'querySessionKey' => 'workTask'));
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
    'post' => array('redirect' => '/doc/create?objectType=mine&objectID=:spaceID&libID=:libID&moduleID=0&docType=', 'data' => 'lib=:libID&module=0&contentType=doc', 'transform' => 'markdown')
);
$routes['/doc/team/spaces/:spaceID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=custom&spaceID=:spaceID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=custom&objectID=:spaceID&libID=:libID&moduleID=0&docType=', 'data' => 'lib=:libID&module=0&contentType=doc', 'transform' => 'markdown')
);
$routes['/doc/product/spaces/:productID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=product&spaceID=:productID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=product&objectID=:productID&libID=:libID&moduleID=0&docType=', 'data' => 'lib=:libID&module=0&contentType=doc', 'transform' => 'markdown')
);
$routes['/doc/project/spaces/:projectID/libs/:libID/docs'] = array(
    'get'  => array('redirect' => '/doc/ajaxGetSpaceData?type=project&spaceID=:projectID&libID=:libID&picks=doc'),
    'post' => array('redirect' => '/doc/create?objectType=project&objectID=:projectID&libID=:libID&moduleID=0&docType=', 'data' => 'lib=:libID&module=0&contentType=doc', 'transform' => 'markdown')
);
$routes['/doc/docs/:docID'] = array(
    'get'    => array('redirect' => '/doc/ajaxGetDoc?docID=:docID', 'transform' => 'markdown'),
    'put'    => array('redirect' => '/doc/edit?docID=:docID', 'data' => 'contentType=doc', 'transform' => 'markdown'),
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

/* AI 知识库接口，由商业扩展提供业务实现。AI knowledge APIs provided by the business extension. */
$knowledgeRoutes = array(
    '/ai/knowledgelibs'                   => '/ai/ajaxGetKnowledgeLibList',
    '/ai/knowledgelibs/:libID/knowledges' => '/ai/ajaxGetKnowledgeItemList',
    '/ai/knowledges/search'               => '/ai/ajaxSearchKnowledgesByKeyword',
    '/ai/knowledges/embeddingssearch'     => '/ai/ajaxSearchKnowledgeChunks',
    '/ai/knowledges/:knowledgeID'         => '/ai/ajaxGetKnowledgeItem'
);
foreach($knowledgeRoutes as $knowledgePath => $knowledgeRedirect)
{
    /* Dispatch unsupported methods to the same entry, where they return 405. */
    $routes[$knowledgePath] = array_fill_keys(array('get', 'post', 'put', 'delete', 'patch', 'head'), array('redirect' => $knowledgeRedirect));
}
unset($knowledgeRoutes, $knowledgePath, $knowledgeRedirect);

/* 数据库结构接口，供 AI 渐进式获取表结构。Database schema APIs for AI. */
$routes['/db/tables'] = array(
    'get' => array('redirect' => '/dev/dbTables', 'response' => '*')
);
$routes['/db/tables/:tableName'] = array(
    'get' => array('redirect' => '/dev/dbTable?tableName=:tableName', 'response' => '*')
);
$routes['/db/query'] = array(
    'post' => array('redirect' => '/dev/dbQuery', 'response' => '*')
);

/* 加载扩展模块的路由配置。Load the route configuration of the extension module. */
foreach(glob(__DIR__ . DS . 'ext' . DS . 'apiv2' . DS . '*.php') as $file)
{
    if(is_file($file)) include_once $file;
}
