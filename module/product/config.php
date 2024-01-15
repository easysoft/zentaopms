<?php
global $lang, $app;

$config->product = new stdclass();
$config->product->showAllProjects = 0;

$config->product->create = new stdclass();
$config->product->edit   = new stdclass();
$config->product->create->requiredFields = 'name';
$config->product->edit->requiredFields   = 'name,code';

$config->product->browse = new stdclass();
$config->product->custom = new stdclass();
$config->product->custom->batchEditFields = 'PO,QD,RD,status,type,acl';
if($config->systemMode == 'ALM') $config->product->custom->batchEditFields .= ',program,line';

/* Export fields of product list page. */
$config->product->list = new stdclass();
$config->product->list->exportFields = 'id,program,line,name,manager,draftStories,activeStories,changedStories,reviewingStories,closedStories,storyCompleteRate,unResolvedBugs,bugFixedRate,plans,releases';

$config->product->actionsMap['normal'] = array('edit');

/* Editor configurations. */
$config->product->editor = new stdclass();
$config->product->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->edit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

/* Configurations for product report. */
$config->product->report = new stdclass();

$config->product->report->stage = array();
$config->product->report->stageLabels[] = 'wait';
$config->product->report->stageLabels[] = 'planed';
$config->product->report->stageLabels[] = 'released';

$config->product->report->planLabels = array();
$config->product->report->planLabels[] = '';

$config->product->report->projectLabels = array();
$config->product->report->projectLabels[] = '';

$config->product->report->planLabels = array();
$config->product->report->planLabels[] = '';

$config->product->statisticFields = array();
$config->product->statisticFields['stories']  = array('draftStories', 'activeStories', 'changingStories', 'reviewingStories', 'closedStories', 'finishedStories', 'finishClosedStories', 'totalStories');
$config->product->statisticFields['bugs']     = array('unresolvedBugs', 'closedBugs', 'fixedBugs');
$config->product->statisticFields['plans']    = array('plans');
$config->product->statisticFields['releases'] = array('releases');

$config->product->skipRedirectMethod = ',create,index,showerrornone,ajaxgetdropmenu,kanban,all,manageline,export,ajaxgetplans,';
$config->product->memberFields       = array('PO', 'QD', 'RD', 'feedback', 'ticket', 'createdBy;');

$config->product->actionList['edit']['icon'] = 'edit';
$config->product->actionList['edit']['text'] = $lang->edit;
$config->product->actionList['edit']['hint'] = $lang->edit;
$config->product->actionList['edit']['url']  = array('module' => 'product', 'method' => 'edit', 'params' => "productID={id}");

$config->product->actionList['close']['icon']        = 'off';
$config->product->actionList['close']['text']        = $lang->product->close;
$config->product->actionList['close']['hint']        = $lang->product->close;
$config->product->actionList['close']['url']         = helper::createLink('product', 'close', 'productID={id}');
$config->product->actionList['close']['data-toggle'] = 'modal';

$config->product->actionList['activate']['icon']        = 'magic';
$config->product->actionList['activate']['text']        = $lang->product->activate;
$config->product->actionList['activate']['hint']        = $lang->product->activate;
$config->product->actionList['activate']['url']         = helper::createLink('product', 'activate', 'productID={id}');
$config->product->actionList['activate']['data-toggle'] = 'modal';

$config->product->actionList['delete']['icon']         = 'trash';
$config->product->actionList['delete']['hint']         = $lang->product->delete;
$config->product->actionList['delete']['url']          = helper::createLink('product', 'delete', 'productID={id}');
$config->product->actionList['delete']['class']        = 'ajax-submit';
$config->product->actionList['delete']['data-confirm'] = $lang->product->confirmDelete;
