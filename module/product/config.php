<?php
global $lang, $app;

$config->product = new stdclass();
$config->product->showAllProjects = 0;

$config->product->customBatchEditFields = 'PO,QD,RD,status,type,acl';
if($config->systemMode == 'ALM') $config->product->customBatchEditFields = 'program,line,' . $config->product->customBatchEditFields;

$config->product->browse = new stdclass();
$config->product->custom = new stdclass();
$config->product->custom->batchEditFields = 'PO,QD,RD';
if($config->systemMode == 'ALM') $config->product->custom->batchEditFields .= ',program,line';

/* Export fields of product list page. */
$config->product->list = new stdclass();
$config->product->list->exportFields = 'id,program,line,name,manager,draftStories,activeStories,changedStories,reviewingStories,closedStories,storyCompleteRate,bugs,unResolvedBugs,assignToNullBugs,bugFixedRate,plans,releases';

$config->product->showBranchMethod = ',browse,project,track,';

$config->product->actionsMap['normal'] = array('edit');

/* Editor configurations. */
$config->product->editor = new stdclass();
$config->product->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->product->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

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
$config->product->statisticFields['requirements'] = array('draftRequirements', 'activeRequirements', 'changingRequirements', 'reviewingRequirements', 'closedRequirements');
$config->product->statisticFields['stories']      = array('draftStories', 'activeStories', 'changingStories', 'reviewingStories', 'closedStories', 'finishClosedStories', 'unclosedStories');
$config->product->statisticFields['bugs']         = array('unResolvedBugs', 'closedBugs', 'fixedBugs');
$config->product->statisticFields['plans']        = array('plans');
$config->product->statisticFields['releases']     = array('releases');

$config->product->skipRedirectMethod = ',create,index,showerrornone,ajaxgetdropmenu,kanban,all,manageline,export,ajaxgetplans,';
