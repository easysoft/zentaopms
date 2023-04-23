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
$config->product->list->exportFields = 'id,program,line,name,manager,draftStories,activeStories,changedStories,reviewingStories,closedStories,storyCompleteRate,bugs,unResolvedBugs,assignToNullBugs,bugFixedRate,plans,releases';

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
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['fields']['code'] = $lang->product->code;
$config->product->all->search['fields']['id']          = $lang->product->id;
if($config->systemMode == 'ALM')
{
    $config->product->all->search['fields']['program'] = $lang->product->program;
    $config->product->all->search['fields']['line']    = $lang->product->line;
}
$config->product->all->search['fields']['desc']        = $lang->product->desc;
$config->product->all->search['fields']['PO']          = $lang->product->PO;
$config->product->all->search['fields']['QD']          = $lang->product->QD;
$config->product->all->search['fields']['RD']          = $lang->product->RD;
$config->product->all->search['fields']['reviewer']    = $lang->product->reviewer;
$config->product->all->search['fields']['type']        = $lang->product->type;
$config->product->all->search['fields']['createdDate'] = $lang->product->createdDate;
$config->product->all->search['fields']['createdBy']   = $lang->product->createdBy;

$config->product->all->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
if(isset($config->setCode) and $config->setCode == 1) $config->product->all->search['params']['code'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->all->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
if($config->systemMode == 'ALM')
{
    $config->product->all->search['params']['program'] = array('operator' => '=', 'control' => 'select', 'values' => '');
    $config->product->all->search['params']['line']    = array('operator' => '=', 'control' => 'select', 'values' => '');
}
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

$config->product->create->fields['program']   = array('control' => 'select', 'options' => '');
$config->product->create->fields['name']      = array('control' => 'input');
$config->product->create->fields['code']      = array('control' => 'input');
$config->product->create->fields['PO']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['QD']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['RD']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['reviewer']  = array('control' => 'select', 'options' => 'users');
$config->product->create->fields['type']      = array('control' => 'select', 'options' => $lang->product->typeList);
$config->product->create->fields['desc']      = array('control' => 'textarea');
$config->product->create->fields['acl']       = array('control' => 'radio', 'options' => $lang->product->aclList);
$config->product->create->fields['whitelist'] = array('control' => 'multi-select', 'options' => 'users');

$config->product->edit->fields['program']   = array('control' => 'select', 'options' => '');
$config->product->edit->fields['line']      = array('control' => 'select', 'options' => '');
$config->product->edit->fields['name']      = array('control' => 'input');
$config->product->edit->fields['code']      = array('control' => 'input');
$config->product->edit->fields['PO']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['QD']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['RD']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['reviewer']  = array('control' => 'select', 'options' => 'users');
$config->product->edit->fields['type']      = array('control' => 'select', 'options' => $lang->product->typeList);
$config->product->edit->fields['status']    = array('control' => 'select', 'options' => $lang->product->statusList);
$config->product->edit->fields['desc']      = array('control' => 'textarea');
$config->product->edit->fields['acl']       = array('control' => 'radio', 'options' => $lang->product->aclList);
$config->product->edit->fields['whitelist'] = array('control' => 'multi-select', 'options' => 'users');

$config->product->all->dtable = new stdclass();
$config->product->all->dtable->fieldList['name']['name']         = 'name';
$config->product->all->dtable->fieldList['name']['title']        = $lang->product->name;
$config->product->all->dtable->fieldList['name']['minWidth']     = 212;
$config->product->all->dtable->fieldList['name']['fixed']        = 'left';
$config->product->all->dtable->fieldList['name']['type']         = 'link';
$config->product->all->dtable->fieldList['name']['flex']         = 1;
$config->product->all->dtable->fieldList['name']['nestedToggle'] = false;
$config->product->all->dtable->fieldList['name']['checkbox']     = true;
$config->product->all->dtable->fieldList['name']['iconRender']   = true;
$config->product->all->dtable->fieldList['name']['sortType']     = true;
$config->product->all->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(row){return row.data.type === \'program\' ? \'icon-cards-view text-gray\' : \'\'}>RAWJS';
$config->product->all->dtable->fieldList['name']['align']        = 'left';

$config->product->all->dtable->fieldList['productLine']['name']     = 'productLine';
$config->product->all->dtable->fieldList['productLine']['title']    = $lang->product->belongingLine;
$config->product->all->dtable->fieldList['productLine']['minWidth'] = 114;
$config->product->all->dtable->fieldList['productLine']['type']     = 'format';
$config->product->all->dtable->fieldList['productLine']['sortType'] = true;
$config->product->all->dtable->fieldList['productLine']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['productLine']['border']   = 'right';
$config->product->all->dtable->fieldList['productLine']['align']    = 'left';
$config->product->all->dtable->fieldList['productLine']['flex']     = 1;

$config->product->all->dtable->fieldList['PO']['name']     = 'PO';
$config->product->all->dtable->fieldList['PO']['title']    = $lang->product->manager;
$config->product->all->dtable->fieldList['PO']['minWidth'] = 104;
$config->product->all->dtable->fieldList['PO']['type']     = 'avatarBtn';
$config->product->all->dtable->fieldList['PO']['sortType'] = false;
$config->product->all->dtable->fieldList['PO']['border']   = 'right';
$config->product->all->dtable->fieldList['PO']['align']    = 'left';

$config->product->all->dtable->fieldList['feedback']['name']     = 'feedback';
$config->product->all->dtable->fieldList['feedback']['title']    = $lang->product->feedback;
$config->product->all->dtable->fieldList['feedback']['minWidth'] = 62;
$config->product->all->dtable->fieldList['feedback']['type']     = 'format';
$config->product->all->dtable->fieldList['feedback']['sortType'] = false;
$config->product->all->dtable->fieldList['feedback']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['feedback']['border']   = 'right';
$config->product->all->dtable->fieldList['feedback']['align']    = 'center';

$config->product->all->dtable->fieldList['draftStories']['name']     = 'draftStories';
$config->product->all->dtable->fieldList['draftStories']['title']    = $lang->product->draftStory;
$config->product->all->dtable->fieldList['draftStories']['minWidth'] = 82;
$config->product->all->dtable->fieldList['draftStories']['type']     = 'format';
$config->product->all->dtable->fieldList['draftStories']['sortType'] = false;
$config->product->all->dtable->fieldList['draftStories']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['draftStories']['align']    = 'center';

$config->product->all->dtable->fieldList['activeStories']['name']     = 'activeStories';
$config->product->all->dtable->fieldList['activeStories']['title']    = $lang->product->activeStory;
$config->product->all->dtable->fieldList['activeStories']['minWidth'] = 62;
$config->product->all->dtable->fieldList['activeStories']['type']     = 'format';
$config->product->all->dtable->fieldList['activeStories']['sortType'] = false;
$config->product->all->dtable->fieldList['activeStories']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['activeStories']['align']    = 'center';

$config->product->all->dtable->fieldList['changingStories']['name']     = 'changingStories';
$config->product->all->dtable->fieldList['changingStories']['title']    = $lang->product->changingStory;
$config->product->all->dtable->fieldList['changingStories']['minWidth'] = 62;
$config->product->all->dtable->fieldList['changingStories']['type']     = 'format';
$config->product->all->dtable->fieldList['changingStories']['sortType'] = false;
$config->product->all->dtable->fieldList['changingStories']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['changingStories']['align']    = 'center';

$config->product->all->dtable->fieldList['reviewingStories']['name']     = 'reviewingStories';
$config->product->all->dtable->fieldList['reviewingStories']['title']    = $lang->product->reviewingStory;
$config->product->all->dtable->fieldList['reviewingStories']['minWidth'] = 62;
$config->product->all->dtable->fieldList['reviewingStories']['type']     = 'format';
$config->product->all->dtable->fieldList['reviewingStories']['sortType'] = false;
$config->product->all->dtable->fieldList['reviewingStories']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['reviewingStories']['align']    = 'center';

$config->product->all->dtable->fieldList['storyCompleteRate']['name']     = 'storyCompleteRate';
$config->product->all->dtable->fieldList['storyCompleteRate']['title']    = $lang->product->storyCompleteRate;
$config->product->all->dtable->fieldList['storyCompleteRate']['minWidth'] = 62;
$config->product->all->dtable->fieldList['storyCompleteRate']['type']     = 'circleProgress';
$config->product->all->dtable->fieldList['storyCompleteRate']['sortType'] = false;
$config->product->all->dtable->fieldList['storyCompleteRate']['group']    = $lang->SRCommon;
$config->product->all->dtable->fieldList['storyCompleteRate']['border']   = 'right';

$config->product->all->dtable->fieldList['plans']['name']     = 'plans';
$config->product->all->dtable->fieldList['plans']['title']    = $lang->product->plan;
$config->product->all->dtable->fieldList['plans']['minWidth'] = 66;
$config->product->all->dtable->fieldList['plans']['type']     = 'format';
$config->product->all->dtable->fieldList['plans']['sortType'] = false;
$config->product->all->dtable->fieldList['plans']['border']   = 'right';
$config->product->all->dtable->fieldList['plans']['align']    = 'center';

$config->product->all->dtable->fieldList['execution']['name']     = 'execution';
$config->product->all->dtable->fieldList['execution']['title']    = $lang->execution->common;
$config->product->all->dtable->fieldList['execution']['minWidth'] = 66;
$config->product->all->dtable->fieldList['execution']['type']     = 'format';
$config->product->all->dtable->fieldList['execution']['sortType'] = false;
$config->product->all->dtable->fieldList['execution']['border']   = 'right';
$config->product->all->dtable->fieldList['execution']['align']    = 'center';

$config->product->all->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->product->all->dtable->fieldList['testCaseCoverage']['title']    = $lang->product->testCaseCoverage;
$config->product->all->dtable->fieldList['testCaseCoverage']['minWidth'] = 86;
$config->product->all->dtable->fieldList['testCaseCoverage']['type']     = 'circleProgress';
$config->product->all->dtable->fieldList['testCaseCoverage']['sortType'] = false;
$config->product->all->dtable->fieldList['testCaseCoverage']['border']   = 'right';

$config->product->all->dtable->fieldList['unResolvedBugs']['name']     = 'unResolvedBugs';
$config->product->all->dtable->fieldList['unResolvedBugs']['title']    = $lang->product->activatedBug;
$config->product->all->dtable->fieldList['unResolvedBugs']['minWidth'] = 62;
$config->product->all->dtable->fieldList['unResolvedBugs']['type']     = 'format';
$config->product->all->dtable->fieldList['unResolvedBugs']['sortType'] = false;
$config->product->all->dtable->fieldList['unResolvedBugs']['group']    = 'Bug';
$config->product->all->dtable->fieldList['unResolvedBugs']['align']    = 'center';

$config->product->all->dtable->fieldList['bugFixedRate']['name']     = 'bugFixedRate';
$config->product->all->dtable->fieldList['bugFixedRate']['title']    = $lang->product->bugFixedRate;
$config->product->all->dtable->fieldList['bugFixedRate']['minWidth'] = 62;
$config->product->all->dtable->fieldList['bugFixedRate']['type']     = 'circleProgress';
$config->product->all->dtable->fieldList['bugFixedRate']['sortType'] = false;
$config->product->all->dtable->fieldList['bugFixedRate']['group']    = 'Bug';
$config->product->all->dtable->fieldList['bugFixedRate']['border']   = 'right';

$config->product->all->dtable->fieldList['releases']['name']     = 'releases';
$config->product->all->dtable->fieldList['releases']['title']    = $lang->product->release;
$config->product->all->dtable->fieldList['releases']['minWidth'] = 68;
$config->product->all->dtable->fieldList['releases']['type']     = 'format';
$config->product->all->dtable->fieldList['releases']['sortType'] = false;
$config->product->all->dtable->fieldList['releases']['align']    = 'center';

$config->product->actionsMap['normal'] = array('edit');

$config->product->editor = new stdclass();
$config->product->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

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
$config->product->statisticFields['requirements'] = array('draftRequirements', 'activeRequirements', 'changingRequirements', 'reviewingRequirements', 'closedRequirements');
$config->product->statisticFields['stories']      = array('draftStories', 'activeStories', 'changingStories', 'reviewingStories', 'closedStories', 'finishClosedStories', 'unclosedStories');
$config->product->statisticFields['bugs']         = array('unResolvedBugs', 'closedBugs', 'fixedBugs');
$config->product->statisticFields['plans']        = array('plans');
$config->product->statisticFields['releases']     = array('releases');

$config->product->skipRedirectMethod = ',create,index,showerrornone,ajaxgetdropmenu,kanban,all,manageline,export,ajaxgetplans,';
