<?php
$config->custom = new stdClass();
$config->custom->canAdd['epic']        = 'reasonList,sourceList,priList,categoryList';
$config->custom->canAdd['story']       = 'reasonList,sourceList,priList,categoryList';
$config->custom->canAdd['requirement'] = 'reasonList,sourceList,priList,categoryList';
$config->custom->canAdd['task']        = 'priList,typeList,reasonList';
$config->custom->canAdd['bug']         = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase']    = 'priList,typeList,stageList,resultList,statusList';
$config->custom->canAdd['testtask']    = 'priList,typeList';
$config->custom->canAdd['todo']        = 'priList,typeList';
$config->custom->canAdd['user']        = 'roleList';
$config->custom->canAdd['block']       = '';
$config->custom->canAdd['project']     = 'unitList';

$config->custom->noModuleMenu = array();

$config->custom->requiredModules[5]  = 'product';
$config->custom->requiredModules[10] = 'epic';
$config->custom->requiredModules[15] = 'requirement';
$config->custom->requiredModules[20] = 'story';
$config->custom->requiredModules[25] = 'productplan';
$config->custom->requiredModules[30] = 'release';

$config->custom->requiredModules[30] = 'project';
$config->custom->requiredModules[35] = 'execution';
$config->custom->requiredModules[40] = 'task';
$config->custom->requiredModules[45] = 'build';

$config->custom->allFeatures     = array('program', 'productRR', 'productUR', 'productLine', 'projectScrum', 'projectWaterfall', 'projectKanban', 'projectAgileplus', 'projectWaterfallplus', 'execution', 'qa', 'devops', 'ai', 'report', 'kanban', 'doc', 'system', 'admin', 'vision');
$config->custom->dataFeatures    = array('productER', 'productUR', 'waterfall', 'waterfallplus');
$config->custom->projectFeatures = array();

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
$config->custom->fieldList['epic']['create']         = 'module,plan,source,pri,assignedTo,estimate,keywords,spec,verify,files';
$config->custom->fieldList['epic']['change']         = 'comment,spec,verify';
$config->custom->fieldList['epic']['close']          = 'comment';
$config->custom->fieldList['epic']['review']         = 'reviewedDate,comment';
$config->custom->fieldList['story']['create']        = 'module,plan,source,pri,assignedTo,estimate,keywords,spec,verify,files';
$config->custom->fieldList['story']['change']        = 'comment,spec,verify';
$config->custom->fieldList['story']['close']         = 'comment';
$config->custom->fieldList['story']['review']        = 'reviewedDate,comment';
$config->custom->fieldList['requirement']['create']  = 'module,plan,source,pri,assignedTo,estimate,keywords,spec,verify,files';
$config->custom->fieldList['requirement']['change']  = 'comment,spec,verify';
$config->custom->fieldList['requirement']['close']   = 'comment';
$config->custom->fieldList['requirement']['review']  = 'reviewedDate,comment';
$config->custom->fieldList['productplan']            = 'begin,end,desc';
$config->custom->fieldList['release']['create']      = 'desc';
$config->custom->fieldList['release']['edit']        = 'desc';
$config->custom->fieldList['execution']['create']    = 'days,desc,PO,PM,QD,RD';
$config->custom->fieldList['execution']['edit']      = 'days,desc,PO,PM,QD,RD';
$config->custom->fieldList['task']['create']         = 'module,story,pri,assignedTo,estimate,desc,estStarted,deadline';
$config->custom->fieldList['task']['edit']           = 'module,pri,assignedTo,estimate,estStarted,deadline';
$config->custom->fieldList['task']['finish']         = 'comment';
$config->custom->fieldList['task']['activate']       = 'assignedTo,comment';
$config->custom->fieldList['build']                  = 'scmPath,filePath,desc';
$config->custom->fieldList['bug']['create']          = 'module,plan,project,execution,assignedTo,deadline,type,feedbackBy,os,browser,severity,pri,steps,keywords';
$config->custom->fieldList['bug']['edit']            = 'module,plan,project,execution,assignedTo,deadline,type,feedbackBy,os,browser,severity,pri,steps,keywords';
$config->custom->fieldList['bug']['resolve']         = 'resolvedBuild,resolvedDate,assignedTo,comment';
$config->custom->fieldList['testcase']['create']     = 'stage,story,pri,precondition,keywords,scene,module';
$config->custom->fieldList['testcase']['edit']       = 'stage,story,pri,precondition,keywords,scene,status,module';
$config->custom->fieldList['testsuite']              = 'desc';
$config->custom->fieldList['caselib']                = 'desc';
$config->custom->fieldList['testcase']['createcase'] = 'lib,stage,pri,precondition,keywords';
$config->custom->fieldList['testreport']             = 'begin,end,members,report';
$config->custom->fieldList['testtask']               = 'owner,pri,desc';
$config->custom->fieldList['doc']                    = 'keywords,content';
$config->custom->fieldList['user']['create']         = 'dept,role,group,email,commiter';
$config->custom->fieldList['user']['edit']           = 'dept,role,group,email,commiter,skype,qq,mobile,phone,address,zipcode,dingding,slack,whatsapp,weixin';

if(!empty($config->setCode))
{
    $config->custom->fieldList['project']['create']   .= ',code';
    $config->custom->fieldList['project']['edit']     .= ',code';
    $config->custom->fieldList['product']['create']   .= ',code';
    $config->custom->fieldList['product']['edit']     .= ',code';
    $config->custom->fieldList['execution']['create'] .= ',code';
    $config->custom->fieldList['execution']['edit']   .= ',code';
}

$config->custom->notSetMethods = array('required', 'browsestoryconcept', 'product', 'role', 'execution', 'limitTaskDate', 'hours', 'project');

$config->custom->customFields = array();
$config->custom->customFields['common']      = array('global' => array('hideVisionTips'));
$config->custom->customFields['doc']         = array('common' => array('docContentType'));
$config->custom->customFields['bug']         = array('custom' => array('createFields', 'batchCreateFields', 'batchEditFields'));
$config->custom->customFields['caselib']     = array('custom' => array('createFields'));
$config->custom->customFields['project']     = array('custom' => array('createFields'));
$config->custom->customFields['execution']   = array('custom' => array('createFields', 'batchEditFields'));
$config->custom->customFields['product']     = array('custom' => array('createFields', 'batchEditFields'), 'trackFields' => array('epic', 'requirement', 'story'));
$config->custom->customFields['programplan'] = array('custom' => array('createWaterfallFields', 'createIpdFields', 'createWaterfallplusFields'), 'customAgilePlus' => array('createFields'));
$config->custom->customFields['story']       = array('custom' => array('createFields', 'batchCreateFields', 'batchEditFields'));
$config->custom->customFields['epic']        = $config->custom->customFields['story'];
$config->custom->customFields['requirement'] = $config->custom->customFields['story'];
$config->custom->customFields['task']        = array('custom' => array('createFields', 'batchCreateFields', 'batchEditFields'));
$config->custom->customFields['testcase']    = array('custom' => array('createFields', 'batchCreateFields', 'batchEditFields'));
$config->custom->customFields['todo']        = array('custom' => array('batchCreateFields', 'batchEditFields'));
$config->custom->customFields['user']        = array('custom' => array('batchCreateFields', 'batchEditFields'));

global $lang;
$config->custom->commonLang = array('$ERCOMMON' => $lang->ERCommon, '$URCOMMON' => $lang->URCommon, '$SRCOMMON' => $lang->SRCommon, '$PRODUCTCOMMON' => $lang->productCommon, '$PROJECTCOMMON' => $lang->projectCommon, '$EXECUTIONCOMMON' => $lang->executionCommon);

$config->custom->browseStoryConcept = new stdclass();

$config->custom->browseStoryConcept->actionList['edit']['icon']        = 'edit';
$config->custom->browseStoryConcept->actionList['edit']['hint']        = $lang->edit;
$config->custom->browseStoryConcept->actionList['edit']['url']         = array('module' => 'custom', 'method' => 'editStoryConcept', 'params' => 'id={key}');
$config->custom->browseStoryConcept->actionList['edit']['data-toggle'] = 'modal';
$config->custom->browseStoryConcept->actionList['edit']['data-size']   = 'sm';

$config->custom->browseStoryConcept->actionList['delete']['icon']         = 'trash';
$config->custom->browseStoryConcept->actionList['delete']['hint']         = $lang->delete;
$config->custom->browseStoryConcept->actionList['delete']['url']          = array('module' => 'custom', 'method' => 'deleteStoryConcept', 'params' => 'id={key}');
$config->custom->browseStoryConcept->actionList['delete']['className']    = 'ajax-submit';
$config->custom->browseStoryConcept->actionList['delete']['data-confirm'] = array('message' => $lang->custom->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->custom->nonInterface = new stdclass();
$config->custom->nonInterface->lang = array('epic-stageList', 'requirement-stageList');
