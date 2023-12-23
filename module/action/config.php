<?php
$config->action->objectNameFields['product']      = 'name';
$config->action->objectNameFields['story']        = 'title';
$config->action->objectNameFields['requirement']  = 'title';
$config->action->objectNameFields['productplan']  = 'title';
$config->action->objectNameFields['release']      = 'name';
$config->action->objectNameFields['program']      = 'name';
$config->action->objectNameFields['project']      = 'name';
$config->action->objectNameFields['execution']    = 'name';
$config->action->objectNameFields['task']         = 'name';
$config->action->objectNameFields['build']        = 'name';
$config->action->objectNameFields['bug']          = 'title';
$config->action->objectNameFields['testcase']     = 'title';
$config->action->objectNameFields['case']         = 'title';
$config->action->objectNameFields['testtask']     = 'name';
$config->action->objectNameFields['user']         = 'account';
$config->action->objectNameFields['api']          = 'title';
$config->action->objectNameFields['doc']          = 'title';
$config->action->objectNameFields['doclib']       = 'name';
$config->action->objectNameFields['todo']         = 'name';
$config->action->objectNameFields['branch']       = 'name';
$config->action->objectNameFields['module']       = 'name';
$config->action->objectNameFields['testsuite']    = 'name';
$config->action->objectNameFields['caselib']      = 'name';
$config->action->objectNameFields['testreport']   = 'title';
$config->action->objectNameFields['entry']        = 'name';
$config->action->objectNameFields['webhook']      = 'name';
$config->action->objectNameFields['risk']         = 'name';
$config->action->objectNameFields['issue']        = 'title';
$config->action->objectNameFields['design']       = 'name';
$config->action->objectNameFields['stakeholder']  = 'user';
$config->action->objectNameFields['budget']       = 'name';
$config->action->objectNameFields['job']          = 'name';
$config->action->objectNameFields['team']         = 'name';
$config->action->objectNameFields['pipeline']     = 'name';
$config->action->objectNameFields['mr']           = 'title';
$config->action->objectNameFields['reviewcl']     = 'title';
$config->action->objectNameFields['kanbancolumn'] = 'name';
$config->action->objectNameFields['kanbanlane']   = 'name';
$config->action->objectNameFields['kanbanspace']  = 'name';
$config->action->objectNameFields['kanbanregion'] = 'name';
$config->action->objectNameFields['kanban']       = 'name';
$config->action->objectNameFields['kanbancard']   = 'name';
$config->action->objectNameFields['sonarqube']    = 'name';
$config->action->objectNameFields['gitlab']       = 'name';
$config->action->objectNameFields['gitea']        = 'name';
$config->action->objectNameFields['gogs']         = 'name';
$config->action->objectNameFields['jenkins']      = 'name';
$config->action->objectNameFields['nexus']        = 'name';
$config->action->objectNameFields['stage']        = 'name';
$config->action->objectNameFields['apistruct']    = 'name';
$config->action->objectNameFields['repo']         = 'name';
$config->action->objectNameFields['dataview']     = 'name';
$config->action->objectNameFields['zahost']       = 'name';
$config->action->objectNameFields['zanode']       = 'name';
$config->action->objectNameFields['privlang']     = 'name';
$config->action->objectNameFields['scene']        = 'title';
$config->action->objectNameFields['pivot']        = 'name';
$config->action->objectNameFields['serverroom']   = 'name';
$config->action->objectNameFields['account']      = 'name';
$config->action->objectNameFields['host']         = 'name';
$config->action->objectNameFields['instance']     = 'name';
$config->action->objectNameFields['space']        = 'name';
$config->action->objectNameFields['solution']     = 'name';
$config->action->objectNameFields['artifactrepo'] = 'name';
$config->action->objectNameFields['prompt']       = 'name';

$config->action->commonImgSize = 870;

$config->action->majorList = array();
$config->action->majorList['task']      = array('assigned', 'finished', 'activated');
$config->action->majorList['bug']       = array('assigned', 'resolved');
$config->action->majorList['release']   = array('opened');
$config->action->majorList['build']     = array('opened');
$config->action->majorList['product']   = array('opened', 'edited');
$config->action->majorList['program']   = array('opened', 'edited');
$config->action->majorList['project']   = array('opened', 'edited');
$config->action->majorList['execution'] = array('opened', 'edited');
$config->action->majorList['doc']       = array('releaseddoc', 'collected');

$config->action->needGetProjectType       = 'build,task,bug,case,testcase,caselib,testtask,testsuite,testreport,doc,issue,release,risk,design,opportunity,trainplan,gapanalysis,researchplan,researchreport,';
$config->action->needGetRelateField       = ',branch,story,productplan,release,task,build,bug,testcase,case,testtask,testreport,doc,doclib,issue,risk,opportunity,trainplan,gapanalysis,team,whitelist,researchplan,researchreport,meeting,kanbanlane,kanbancolumn,module,review,';
$config->action->noLinkModules            = ',doclib,module,webhook,gitlab,instance,gitea,gogs,sonarqube,pipeline,jenkins,kanban,kanbanspace,kanbancolumn,kanbanlane,kanbanregion,kanbancard,execution,project,traincategory,apistruct,program,product,user,entry,repo,pivot,scene,';
$config->action->ignoreObjectType4Dynamic = 'kanbanregion,kanbanlane,kanbancolumn';
$config->action->ignoreActions4Dynamic    = 'disconnectxuanxuan,reconnectxuanxuan,loginxuanxuan,logoutxuanxuan,editmr,removemr';

$config->action->latestDateList = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'thisMonth');

$config->action->preferredTypeNum = 10;

$config->action->preferredType = new stdclass();
$config->action->preferredType->ALM   = array('user', 'story', 'task', 'bug', 'case', 'doc', 'program', 'product', 'project', 'execution');
$config->action->preferredType->light = array('user', 'story', 'task', 'bug', 'case', 'doc', 'product', 'project', 'execution');

global $app, $lang;
$app->loadLang('action');

$config->trash = new stdclass();
$config->trash->search['module']               = 'trash';
$config->trash->search['fields']['objectName'] = $lang->action->objectName;
$config->trash->search['fields']['objectID']   = $lang->action->objectID;
$config->trash->search['fields']['actor']      = $lang->action->actor;
$config->trash->search['fields']['date']       = $lang->action->dateAB;

$config->trash->search['params']['objectName'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trash->search['params']['objectID']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->trash->search['params']['actor']      = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->trash->search['params']['date']       = array('operator' => '=', 'control' => 'date',  'values' => '');

$config->action->newPageModule  = array('repo', 'mr', 'host', 'account', 'serverroom', 'instance', 'store', 'space', 'domain', 'service', 'gitlab', 'gitea', 'gogs', 'sonarqube', 'jenkins', 'nexus');
$config->action->latestDateList = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'thisMonth');
