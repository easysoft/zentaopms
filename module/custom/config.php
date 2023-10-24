<?php
$config->custom = new stdClass();
$config->custom->canAdd['story']    = 'reasonList,sourceList,priList,categoryList';
$config->custom->canAdd['task']     = 'priList,typeList,reasonList';
$config->custom->canAdd['bug']      = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase'] = 'priList,typeList,stageList,resultList,statusList';
$config->custom->canAdd['testtask'] = 'priList,typeList';
$config->custom->canAdd['todo']     = 'priList,typeList';
$config->custom->canAdd['user']     = 'roleList';
$config->custom->canAdd['block']    = '';
$config->custom->canAdd['project']  = 'unitList';

$config->custom->noModuleMenu = array();

$config->custom->requiredModules[10] = 'product';
$config->custom->requiredModules[15] = 'story';
$config->custom->requiredModules[20] = 'productplan';
$config->custom->requiredModules[25] = 'release';

$config->custom->requiredModules[30] = 'project';
$config->custom->requiredModules[35] = 'execution';
$config->custom->requiredModules[40] = 'task';
$config->custom->requiredModules[45] = 'build';

$config->custom->allFeatures   = array('program', 'productRR', 'productUR', 'productLine', 'projectScrum', 'projectWaterfall', 'projectKanban', 'projectAgileplus', 'projectWaterfallplus', 'execution', 'qa', 'devops', 'kanban', 'doc', 'report', 'system', 'admin', 'vision');
$config->custom->dataFeatures  = array('productUR', 'waterfall', 'waterfallplus');
$config->custom->scrumFeatures = array();

$config->custom->requiredModules[50] = 'bug';
$config->custom->requiredModules[55] = 'testcase';
$config->custom->requiredModules[60] = 'testsuite';
$config->custom->requiredModules[65] = 'testreport';
$config->custom->requiredModules[70] = 'caselib';
$config->custom->requiredModules[75] = 'testtask';

$config->custom->requiredModules[80] = 'doc';

$config->custom->requiredModules[85] = 'user';

$config->custom->fieldList['program']['create']      = 'budget,PM,desc';
$config->custom->fieldList['program']['edit']        = 'budget,PM,desc';
$config->custom->fieldList['project']['create']      = 'budget,PM,desc';
$config->custom->fieldList['project']['edit']        = 'budget,PM,desc';
$config->custom->fieldList['product']['create']      = 'PO,QD,RD,type,desc';
$config->custom->fieldList['product']['edit']        = 'PO,QD,RD,type,desc,status';
$config->custom->fieldList['story']['create']        = 'module,plan,source,pri,estimate,keywords,spec,verify';
$config->custom->fieldList['story']['change']        = 'comment,spec,verify';
$config->custom->fieldList['story']['close']         = 'comment';
$config->custom->fieldList['story']['review']        = 'reviewedDate,comment';
$config->custom->fieldList['productplan']            = 'begin,end,desc';
$config->custom->fieldList['release']['create']      = 'desc';
$config->custom->fieldList['release']['edit']        = 'desc';
$config->custom->fieldList['execution']['create']    = 'days,desc,PO,PM,QD,RD';
$config->custom->fieldList['execution']['edit']      = 'days,desc,PO,PM,QD,RD';
$config->custom->fieldList['task']['create']         = 'module,story,pri,estimate,desc,estStarted,deadline';
$config->custom->fieldList['task']['edit']           = 'module,pri,estimate,estStarted,deadline';
$config->custom->fieldList['task']['finish']         = 'comment';
$config->custom->fieldList['task']['activate']       = 'assignedTo,comment';
$config->custom->fieldList['build']                  = 'scmPath,filePath,desc';
$config->custom->fieldList['bug']['create']          = 'module,project,execution,deadline,type,os,browser,severity,pri,steps,keywords';
$config->custom->fieldList['bug']['edit']            = 'plan,project,assignedTo,deadline,type,os,browser,severity,pri,steps,keywords';
$config->custom->fieldList['bug']['resolve']         = 'resolvedBuild,resolvedDate,assignedTo,comment';
$config->custom->fieldList['testcase']['create']     = 'stage,story,pri,precondition,keywords,module';
$config->custom->fieldList['testcase']['edit']       = 'stage,story,pri,precondition,keywords,status,module';
$config->custom->fieldList['testsuite']              = 'desc';
$config->custom->fieldList['caselib']                = 'desc';
$config->custom->fieldList['testcase']['createcase'] = 'lib,stage,pri,precondition,keywords';
$config->custom->fieldList['testreport']             = 'begin,end,members,report';
$config->custom->fieldList['testtask']               = 'owner,pri,desc';
$config->custom->fieldList['doc']                    = 'keywords,content';
$config->custom->fieldList['user']['create']         = 'dept,role,email,commiter';
$config->custom->fieldList['user']['edit']           = 'dept,role,email,commiter,skype,qq,mobile,phone,address,zipcode,dingding,slack,whatsapp,weixin';

$config->custom->notSetMethods = array('required', 'browsestoryconcept', 'product', 'role', 'execution', 'limitTaskDate');

global $lang;
$config->custom->commonLang = array('$URCOMMON' => $lang->URCommon, '$SRCOMMON' => $lang->SRCommon, '$PRODUCTCOMMON' => $lang->productCommon, '$PROJECTCOMMON' => $lang->projectCommon, '$EXECUTIONCOMMON' => $lang->executionCommon);
