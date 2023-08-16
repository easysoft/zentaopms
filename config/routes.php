<?php
/*
 * The routes for API.
 */
$routes = array();

$routes['/tokens']   = 'tokens';
$routes['/langs']    = 'langs';
$routes['/views']    = 'views';
$routes['/groups']   = 'groups';
$routes['/ping']     = 'ping';
$routes['/comments'] = 'comments';

$routes['/tabs/:module'] = 'tabs';

$routes['/files']     = 'files';
$routes['/files/:id'] = 'file';

$routes['/feedbacks']            = 'feedbacks';
$routes['/feedbacks/:id']        = 'feedback';
$routes['/feedbacks/:id/assign'] = 'feedbackAssignto';
$routes['/feedbacks/:id/close']  = 'feedbackClose';

$routes['/tickets']              = 'tickets';
$routes['/tickets/:id']          = 'ticket';
$routes['/tickets/:id/assign']   = 'ticketAssignto';
$routes['/tickets/:id/close']    = 'ticketClose';

$routes['/options/:type'] = 'options';

$routes['/configurations']       = 'configs';
$routes['/configurations/:name'] = 'config';

$routes['/programs/:id/products'] = 'products';
$routes['/products']              = 'products';
$routes['/products/:id']          = 'product';
$routes['/product/:id']           = 'product';

$routes['/productlines']     = 'productLines';
$routes['/productlines/:id'] = 'productLine';

$routes['/productplans']                   = 'productPlans';
$routes['/products/:id/plans']             = 'productPlans';
$routes['/productplans/:id']               = 'productPlan';
$routes['/productplans/:id/linkstories']   = 'productPlanLinkStories';
$routes['/productplans/:id/unlinkstories'] = 'productPlanUnlinkStories';
$routes['/productplans/:id/linkbugs']      = 'productPlanLinkBugs';
$routes['/productplans/:id/unlinkbugs']    = 'productPlanUnlinkBugs';

$routes['/releases']              = 'releases';
$routes['/products/:id/releases'] = 'releases';
$routes['/projects/:id/releases'] = 'projectReleases';
$routes['/releases/:id']          = 'release';

$routes['/stories']                = 'stories';
$routes['/products/:id/stories']   = 'stories';
$routes['/projects/:id/stories']   = 'projectStories';
$routes['/executions/:id/stories'] = 'executionStories';
$routes['/stories/:id']            = 'story';
$routes['/stories/:id/change']     = 'storyChange';
$routes['/stories/:id/close']      = 'storyClose';
$routes['/stories/:id/active']     = 'storyActive';
$routes['/stories/:id/assign']     = 'storyAssignto';
$routes['/stories/:id/estimate']   = 'storyRecordEstimate';
$routes['/stories/:id/child']      = 'storyChild';
$routes['/stories/:id/recall']     = 'storyRecall';
$routes['/stories/:id/review']     = 'storyReview';

$routes['/products/:id/bugs']   = 'bugs';
$routes['/projects/:id/bugs']   = 'projectBugs';
$routes['/executions/:id/bugs'] = 'executionBugs';
$routes['/bugs']                = 'bugs';
$routes['/bugs/:id']            = 'bug';
$routes['/bugs/:id/close']      = 'bugClose';
$routes['/bugs/:id/assign']     = 'bugAssign';
$routes['/bugs/:id/confirm']    = 'bugConfirm';
$routes['/bugs/:id/resolve']    = 'bugResolve';
$routes['/bugs/:id/active']     = 'bugActive';
$routes['/bugs/:id/estimate']   = 'bugRecordEstimate';

$routes['/programs/:id/projects'] = 'projects';
$routes['/products/:id/projects'] = 'productProjects';
$routes['/projects']              = 'projects';
$routes['/projects/:id']          = 'project';

$routes['/projects/:id/executions'] = 'executions';
$routes['/executions']              = 'executions';
$routes['/executions/:id']          = 'execution';

$routes['/executions/:id/tasks/batchCreate'] = 'taskBatchCreate';
$routes['/tasks/batchCreate']                = 'taskBatchCreate';

$routes['/executions/:id/tasks'] = 'tasks';
$routes['/tasks']                = 'tasks';
$routes['/tasks/:id']            = 'task';
$routes['/tasks/:id/assignto']   = 'taskAssignTo';
$routes['/tasks/:id/start']      = 'taskStart';
$routes['/tasks/:id/pause']      = 'taskPause';
$routes['/tasks/:id/restart']    = 'taskRestart';
$routes['/tasks/:id/finish']     = 'taskFinish';
$routes['/tasks/:id/close']      = 'taskClose';
$routes['/tasks/:id/estimate']   = 'taskRecordEstimate';
$routes['/tasks/:id/active']     = 'taskActive';

$routes['/users']     = 'users';
$routes['/users/:id'] = 'user';
$routes['/user']      = 'user';

$routes['/programs']     = 'programs';
$routes['/programs/:id'] = 'program';

$routes['/programs/:id/stakeholders'] = 'stakeholders';

$routes['/products/:id/issues'] = 'productIssues';
$routes['/projects/:id/issues'] = 'issues';
$routes['/issues']              = 'issues';
$routes['/issues/:id']          = 'issue';

$routes['/todos']              = 'todos';
$routes['/todos/:id']          = 'todo';
$routes['/todos/:id/finish']   = 'todoFinish';
$routes['/todos/:id/activate'] = 'todoActivate';

$routes['/projects/:id/builds']   = 'builds';
$routes['/executions/:id/builds'] = 'executionBuilds';
$routes['/builds']                = 'builds';
$routes['/builds/:id']            = 'build';

$routes['/products/:id/testcases']   = 'testcases';
$routes['/projects/:id/testcases']   = 'projectCases';
$routes['/executions/:id/testcases'] = 'executionCases';
$routes['/executions/:id/members']   = 'executionMembers';
$routes['/testcases']                = 'testcases';
$routes['/testcases/:id']            = 'testcase';
$routes['/testcases/:id/results']    = 'testresults';

$routes['/products/:id/testsuites'] = 'testsuites';
$routes['/testsuites']              = 'testsuites';
$routes['/testsuites/:id']          = 'testsuite';

$routes['/projects/:projectID/testtasks'] = 'testtasks';
$routes['/testtasks']                     = 'testtasks';
$routes['/testtasks/:id']                 = 'testtask';

$routes['/projects/:projectID/risks'] = 'risks';
$routes['/risks']                     = 'risks';
$routes['/risks/:id']                 = 'risk';

$routes['/projects/:id/meetings'] = 'meetings';
$routes['/meetings']              = 'meetings';
$routes['/meetings/:id']          = 'meeting';

$routes['/departments']     = 'departments';
$routes['/departments/:id'] = 'department';

$routes['/doclibs']      = 'doclibs';
$routes['/doclibs/:id']  = 'docs';
$routes['/docs']         = 'docs';
$routes['/docs/:id']     = 'doc';

$routes['/repos']       = 'repos';
$routes['/repos/rules'] = 'reporules';
$routes['/jobs']        = 'jobs';
$routes['/mr']          = 'mr';

$routes['/modules'] = 'modules';

$routes['/reports'] = 'reports';

$routes['/host/heartbeat']    = 'hostHeartbeat';
$routes['/host/submitResult'] = 'hostSubmit';

$routes['/ztf/submitResult'] = 'ztfSubmit';

$routes['/z/folders']           = 'zfolders';
$routes['/z/folders/:id']       = 'zfolder';
$routes['/z/files/:id']         = 'zfile';
$routes['/z/files/:id/content'] = 'zfileContent';

$routes['/gitlab/webhook'] = 'gitlabWebhook';

$routes['/ciresults'] = 'ciresults';

$config->routes = $routes;
