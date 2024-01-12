<?php
$config->product = new stdclass();
$config->product->orderBy         = 'isClosed,program_asc,order_asc';
$config->product->showAllProjects = 0;

$config->product->customBatchEditFields = 'PO,QD,RD,status,type,acl';
if($config->systemMode == 'ALM') $config->product->customBatchEditFields = 'program,line,' . $config->product->customBatchEditFields;

$config->product->browse = new stdclass();
$config->product->custom = new stdclass();
$config->product->custom->batchEditFields = 'PO,QD,RD';
if($config->systemMode == 'ALM') $config->product->custom->batchEditFields .= ',program,line';

$config->product->list = new stdclass();
$config->product->list->exportFields = 'id,program,line,name,manager,draftStories,activeStories,changedStories,reviewingStories,closedStories,storyCompleteRate,unResolvedBugs,bugFixedRate,plans,releases';

$config->product->showBranchMethod = 'browse,project';

global $lang, $app;
$app->loadLang('story');
$config->product->search['module']             = 'story';
$config->product->search['fields']['title']    = $lang->story->title;
$config->product->search['fields']['id']       = $lang->story->id;
$config->product->search['fields']['keywords'] = $lang->story->keywords;
$config->product->search['fields']['stage']    = $lang->story->stage;
$config->product->search['fields']['status']   = $lang->story->status;
$config->product->search['fields']['pri']      = $lang->story->pri;

$config->product->search['fields']['product']  = $lang->story->product;
$config->product->search['fields']['branch']   = '';
$config->product->search['fields']['module']   = $lang->story->module;
$config->product->search['fields']['roadmap']  = '';
$config->product->search['fields']['plan']     = $lang->story->plan;
$config->product->search['fields']['estimate'] = $lang->story->estimate;

$config->product->search['fields']['source']     = $lang->story->source;
$config->product->search['fields']['sourceNote'] = $lang->story->sourceNote;
$config->product->search['fields']['fromBug']    = $lang->story->fromBug;
$config->product->search['fields']['category']   = $lang->story->category;

$config->product->search['fields']['openedBy']     = $lang->story->openedBy;
$config->product->search['fields']['reviewedBy']   = $lang->story->reviewedBy;
$config->product->search['fields']['result']       = $lang->story->reviewResultAB;
$config->product->search['fields']['assignedTo']   = $lang->story->assignedTo;
$config->product->search['fields']['closedBy']     = $lang->story->closedBy;
$config->product->search['fields']['lastEditedBy'] = $lang->story->lastEditedBy;

$config->product->search['fields']['mailto']       = $lang->story->mailto;

$config->product->search['fields']['closedReason'] = $lang->story->closedReason;
$config->product->search['fields']['version']      = $lang->story->version;

$config->product->search['fields']['openedDate']     = $lang->story->openedDate;
$config->product->search['fields']['reviewedDate']   = $lang->story->reviewedDate;
$config->product->search['fields']['assignedDate']   = $lang->story->assignedDate;
$config->product->search['fields']['closedDate']     = $lang->story->closedDate;
$config->product->search['fields']['lastEditedDate'] = $lang->story->lastEditedDate;
$config->product->search['fields']['activatedDate']  = $lang->story->activatedDate;

$config->product->search['params']['title']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['keywords']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->statusList);
$config->product->search['params']['stage']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->stageList);
$config->product->search['params']['pri']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->priList);

$config->product->search['params']['product']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['branch']         = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['module']         = array('operator' => 'belong',  'control' => 'select', 'values' => '');
$config->product->search['params']['roadmap']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['plan']           = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['estimate']       = array('operator' => '=',       'control' => 'input',  'values' => '');

$config->product->search['params']['source']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->sourceList);
$config->product->search['params']['sourceNote']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['fromBug']        = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->product->search['params']['category']       = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->story->categoryList);

$config->product->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['reviewedBy']     = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->search['params']['result']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->reviewResultList);
$config->product->search['params']['assignedTo']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['lastEditedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->product->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');

$config->product->search['params']['closedReason']   = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->reasonList);
$config->product->search['params']['version']        = array('operator' => '>=',      'control' => 'input',  'values' => '');

$config->product->search['params']['openedDate']     = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->product->search['params']['reviewedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->product->search['params']['assignedDate']   = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->product->search['params']['closedDate']     = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->product->search['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->product->search['params']['activatedDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');

$app->loadLang('product');
$config->product->all = new stdclass();
$config->product->all->search['module']                = 'product';
$config->product->all->search['fields']['name']        = $lang->product->name;
if($config->systemMode == 'ALM' || $config->systemMode == 'PLM')
{
    $config->product->all->search['fields']['program'] = $lang->product->program;
    $config->product->all->search['fields']['line']    = $lang->product->line;
}
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['fields']['code'] = $lang->product->code;
$config->product->all->search['fields']['id']          = $lang->product->id;
$config->product->all->search['fields']['desc']        = $lang->product->desc;
$config->product->all->search['fields']['PO']          = $lang->product->PO;
$config->product->all->search['fields']['QD']          = $lang->product->QD;
$config->product->all->search['fields']['RD']          = $lang->product->RD;
$config->product->all->search['fields']['reviewer']    = $lang->product->reviewer;
$config->product->all->search['fields']['type']        = $lang->product->type;
$config->product->all->search['fields']['createdDate'] = $lang->product->createdDate;
$config->product->all->search['fields']['createdBy']   = $lang->product->createdBy;

$config->product->all->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
if($config->systemMode == 'ALM' || $config->systemMode == 'PLM')
{
    $config->product->all->search['params']['program'] = array('operator' => '=', 'control' => 'select', 'values' => '');
    $config->product->all->search['params']['line']    = array('operator' => '=', 'control' => 'select', 'values' => '');
}
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['params']['code'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->product->all->search['params']['desc']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['PO']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['QD']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['RD']          = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['reviewer']    = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->all->search['params']['type']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->product->typeList);
$config->product->all->search['params']['createdDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->product->all->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->product->create = new stdclass();
$config->product->edit   = new stdclass();
$config->product->create->requiredFields = 'name,code';
$config->product->edit->requiredFields   = 'name,code';

$config->product->editor = new stdclass();
$config->product->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->product->report = new stdclass();
$config->product->report->stage = array();
$config->product->report->stageLabels[] = 'wait';
$config->product->report->stageLabels[] = 'planed';
$config->product->report->stageLabels[] = 'released';

$config->product->report->planLabels   = array();
$config->product->report->planLabels[] = '';

$config->product->report->projectLabels   = array();
$config->product->report->projectLabels[] = '';

$config->product->report->planLabels   = array();
$config->product->report->planLabels[] = '';

$config->product->statisticFields = array();
if($config->vision == 'or')
{
    $config->product->statisticFields['stories']  = array('draftStories', 'activeStories', 'launchedStories', 'developingStories');
}
else
{
    $config->product->statisticFields['stories']  = array('draftStories', 'activeStories', 'changingStories', 'reviewingStories', 'closedStories', 'finishedStories', 'finishClosedStories', 'totalStories');
    $config->product->statisticFields['bugs']     = array('unresolvedBugs', 'closedBugs', 'fixedBugs');
    $config->product->statisticFields['plans']    = array('plans');
    $config->product->statisticFields['releases'] = array('releases');
}

$config->product->skipRedirectMethod = ',create,index,showerrornone,ajaxgetdropmenu,kanban,all,manageline,export,ajaxgetplans,';
