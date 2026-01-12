<?php
$config->action->objectNameFields['product']      = 'name';
$config->action->objectNameFields['productline']  = 'name';
$config->action->objectNameFields['epic']         = 'title';
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
$config->action->objectNameFields['user']         = 'realname';
$config->action->objectNameFields['api']          = 'title';
$config->action->objectNameFields['board']        = 'name';
$config->action->objectNameFields['boardspace']   = 'name';
$config->action->objectNameFields['doc']          = 'title';
$config->action->objectNameFields['doclib']       = 'name';
$config->action->objectNameFields['docspace']     = 'name';
$config->action->objectNameFields['doctemplate']  = 'title';
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
$config->action->objectNameFields['prompt']       = 'name';
$config->action->objectNameFields['miniprogram']  = 'name';
$config->action->objectNameFields['holiday']      = 'name';
$config->action->objectNameFields['system']       = 'name';
$config->action->objectNameFields['deliverable']  = 'name';

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
$config->action->majorList['doc']       = array('releaseddoc');

$config->action->needGetProjectType       = 'build,task,bug,case,testcase,caselib,testtask,testsuite,testreport,doc,issue,release,risk,design,opportunity,trainplan,gapanalysis,researchplan,researchreport,';
$config->action->needGetRelateField       = ',branch,story,epic,requirement,productplan,release,task,build,bug,testcase,case,testtask,testreport,design,doc,doclib,issue,risk,opportunity,trainplan,gapanalysis,team,whitelist,researchplan,researchreport,meeting,kanbanlane,kanbancolumn,module,review,';
$config->action->noLinkModules            = ',doclib,module,webhook,gitlab,instance,gitea,gogs,sonarqube,pipeline,jenkins,kanban,kanbanspace,kanbancolumn,kanbanlane,kanbanregion,kanbancard,execution,project,traincategory,apistruct,program,product,user,entry,repo,pivot,scene,boardspace,auditplan,auditresult,productline,chapter,doc,caselib,';
$config->action->ignoreObjectType4Dynamic = 'kanbanregion,kanbanlane,kanbancolumn';
$config->action->ignoreActions4Dynamic    = 'disconnectxuanxuan,reconnectxuanxuan,loginxuanxuan,logoutxuanxuan,editmr,removemr,syncdoingbyticket,syncdoingbystory,syncdoingbyuserstory,syncdoingbyepic,syncdoingbytask,syncdoingbybug,syncdoingbytodo,syncdoingbydemand';
if(in_array($config->edition, array('open', 'biz'))) $config->action->ignoreObjectType4Dynamic .= ',reporttemplate';

$config->action->latestDateList = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'thisMonth');

$config->action->preferredTypeNum = 10;

$config->action->preferredType = new stdclass();
$config->action->preferredType->ALM   = array('user', 'story', 'task', 'bug', 'case', 'board', 'boardspace', 'doc', 'program', 'product', 'productline', 'project', 'execution');
$config->action->preferredType->light = array('user', 'story', 'task', 'bug', 'case', 'board', 'boardspace', 'doc', 'product', 'project', 'execution');

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

$config->action->newPageModule  = array('repo', 'mr', 'host', 'account', 'serverroom', 'instance', 'store', 'space', 'domain', 'service', 'gitlab', 'gitea', 'gogs', 'sonarqube', 'jenkins', 'nexus', 'board');
$config->action->latestDateList = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'thisMonth');

$config->action->userFields         = 'openedBy,addedBy,createdBy,editedBy,assignedTo,finishedBy,canceledBy,closedBy,activatedBy,resolvedBy,lastEditedBy,builder,owner,reviewedBy,forwardBy,scriptedBy,manager,commitedBy,archivedBy,PO,QD,RD,feedback,PM,account,changedBy,submitedBy,retractedBy,lastRunner,assignedBy,processedBy';
$config->action->multipleUserFields = 'mailto,whitelist,reviewer,users,assignee,approver,PMT,committer,backReviewers,contributor,reviewers';

$config->action->objectFields['task']['closedReason'] = 'reasonList';

$config->action->multipleObjectFields['bug']['os']        = 'osList';
$config->action->multipleObjectFields['bug']['browser']   = 'browserList';
$config->action->multipleObjectFields['testtask']['type'] = 'typeList';

$config->action->approvalFields['reviewStatus'] = 'reviewStatusList';
$config->action->approvalFields['reviewResult'] = 'reviewResultList';

$config->action->hiddenTrashObjects = 'object,cm';
