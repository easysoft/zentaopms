<?php
global $app, $lang;

$config->codescan->actionList = array();
$config->codescan->actionList['enable']['icon']         = 'active';
$config->codescan->actionList['enable']['hint']         = $lang->codescan->enable;
$config->codescan->actionList['enable']['url']          = array('module' => 'codescan', 'method' => 'changeState', 'params' => 'ruleID={id}&status=1');
$config->codescan->actionList['enable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmEnable, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->actionList['enable']['ajaxSubmit']   = true;
$config->codescan->actionList['enable']['notLoadModel'] = true;
$config->codescan->actionList['enable']['showText']     = true;

$config->codescan->actionList['disable']['icon']         = 'cancel';
$config->codescan->actionList['disable']['hint']         = $lang->codescan->disable;
$config->codescan->actionList['disable']['url']          = array('module' => 'codescan', 'method' => 'changeState', 'params' => 'ruleID={id}&status=2');
$config->codescan->actionList['disable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmDisable, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->actionList['disable']['ajaxSubmit']   = true;
$config->codescan->actionList['disable']['notLoadModel'] = true;
$config->codescan->actionList['disable']['showText']     = true;

$config->codescan->actionList['unlink']['icon']         = 'unlink';
$config->codescan->actionList['unlink']['hint']         = $lang->codescan->unlinkRule;
$config->codescan->actionList['unlink']['url']          = array('module' => 'codescan', 'method' => 'unlinkRule', 'params' => 'setID={setID}&ruleID={id}');
$config->codescan->actionList['unlink']['data-confirm'] = array('message' => $lang->codescan->notice->confirmUnlink, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->actionList['unlink']['ajaxSubmit']   = true;
$config->codescan->actionList['unlink']['notLoadModel'] = true;
$config->codescan->actionList['unlink']['showText']     = true;

/* Search config. */
$config->codescan->search['module']             = 'codeScanRule';
$config->codescan->search['fields']['priority'] = $lang->codescan->severity;
$config->codescan->search['fields']['name']     = $lang->codescan->name;
$config->codescan->search['fields']['lang']     = $lang->codescan->language;
$config->codescan->search['fields']['plugin']   = $lang->codescan->tool;
$config->codescan->search['fields']['type']     = $lang->codescan->type;
$config->codescan->search['fields']['status']   = $lang->codescan->status;
$config->codescan->search['fields']['id']       = $lang->idAB;
$config->codescan->search['fields']['tag']      = $lang->codescan->tag;

$config->codescan->search['params']['priority'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->severityList);
$config->codescan->search['params']['name']     = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->search['params']['lang']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->search['params']['plugin']   = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->search['params']['type']     = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->typeList);
$config->codescan->search['params']['status']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->statusList);
$config->codescan->search['params']['id']       = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->search['params']['tag']      = array('operator' => '=', 'control' => 'select', 'values' => array());

$config->codescan->ruleset = new stdclass();
$config->codescan->ruleset->actionList = array();
$config->codescan->ruleset->actionList['link']['icon']        = 'link';
$config->codescan->ruleset->actionList['link']['hint']        = $lang->codescan->linkRule;
$config->codescan->ruleset->actionList['link']['url']         = array('module' => 'codescan', 'method' => 'linkRule', 'params' => 'rulesetID={id}');
$config->codescan->ruleset->actionList['link']['showText']    = true;
$config->codescan->ruleset->actionList['link']['data-toggle'] = 'modal';
$config->codescan->ruleset->actionList['link']['data-size']   = 'lg';

$config->codescan->ruleset->actionList['enable']['icon']         = 'active';
$config->codescan->ruleset->actionList['enable']['hint']         = $lang->codescan->enable;
$config->codescan->ruleset->actionList['enable']['url']          = array('module' => 'codescan', 'method' => 'changeRulesetState', 'params' => 'rulesetID={id}&status=1');
$config->codescan->ruleset->actionList['enable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmEnableRuleset, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->ruleset->actionList['enable']['ajaxSubmit']   = true;
$config->codescan->ruleset->actionList['enable']['notLoadModel'] = true;
$config->codescan->ruleset->actionList['enable']['showText']     = true;

$config->codescan->ruleset->actionList['disable']['icon']         = 'cancel';
$config->codescan->ruleset->actionList['disable']['hint']         = $lang->codescan->disable;
$config->codescan->ruleset->actionList['disable']['url']          = array('module' => 'codescan', 'method' => 'changeRulesetState', 'params' => 'rulesetID={id}&status=2');
$config->codescan->ruleset->actionList['disable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmDisableRuleset, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->ruleset->actionList['disable']['ajaxSubmit']   = true;
$config->codescan->ruleset->actionList['disable']['notLoadModel'] = true;
$config->codescan->ruleset->actionList['disable']['showText']     = true;

$config->codescan->ruleset->actionList['editset']['icon']        = 'edit';
$config->codescan->ruleset->actionList['editset']['hint']        = $lang->codescan->edit;
$config->codescan->ruleset->actionList['editset']['url']         = array('module' => 'codescan', 'method' => 'editRuleset', 'params' => 'rulesetID={id}');
$config->codescan->ruleset->actionList['editset']['showText']    = true;
$config->codescan->ruleset->actionList['editset']['data-toggle'] = 'modal';

$config->codescan->ruleset->actionList['deleteset']['icon']         = 'trash';
$config->codescan->ruleset->actionList['deleteset']['hint']         = $lang->codescan->delete;
$config->codescan->ruleset->actionList['deleteset']['url']          = array('module' => 'codescan', 'method' => 'deleteRuleset', 'params' => 'rulesetID={id}');
$config->codescan->ruleset->actionList['deleteset']['data-confirm'] = array('message' => $lang->codescan->notice->deleteRuleset, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->ruleset->actionList['deleteset']['ajaxSubmit']   = true;
$config->codescan->ruleset->actionList['deleteset']['showText']     = true;

$config->codescan->ruleset->search['module']              = 'codeScanRuleset';
$config->codescan->ruleset->search['fields']['name']      = $lang->codescan->name;
$config->codescan->ruleset->search['fields']['id']        = $lang->idAB;
$config->codescan->ruleset->search['fields']['plugin']    = $lang->codescan->tool;
$config->codescan->ruleset->search['fields']['lang']      = $lang->codescan->language;
$config->codescan->ruleset->search['fields']['status']    = $lang->codescan->status;

$config->codescan->ruleset->search['params']['name']      = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->ruleset->search['params']['id']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->ruleset->search['params']['plugin']    = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->codescan->ruleset->search['params']['lang']      = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->ruleset->search['params']['status']    = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->statusList);

$config->codescan->actions = new stdclass();
$config->codescan->actions->rulesetview['mainActions']   = array('editset', 'deleteset');
$config->codescan->actions->rulesetview['suffixActions'] = array();

$config->codescan->solution = new stdclass();
$config->codescan->solution->actionList = array();
$config->codescan->solution->actionList['linkset']['icon']        = 'link';
$config->codescan->solution->actionList['linkset']['hint']        = $lang->codescan->linkSet;
$config->codescan->solution->actionList['linkset']['url']         = array('module' => 'codescan', 'method' => 'linkSet', 'params' => 'solutionID={id}');
$config->codescan->solution->actionList['linkset']['showText']    = true;
$config->codescan->solution->actionList['linkset']['data-toggle'] = 'modal';
$config->codescan->solution->actionList['linkset']['data-size']   = 'lg';

$config->codescan->solution->actionList['enable']['icon']         = 'active';
$config->codescan->solution->actionList['enable']['hint']         = $lang->codescan->enable;
$config->codescan->solution->actionList['enable']['url']          = array('module' => 'codescan', 'method' => 'changeSolutionState', 'params' => 'solutionID={id}&status=1');
$config->codescan->solution->actionList['enable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmEnableSolution, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->solution->actionList['enable']['ajaxSubmit']   = true;
$config->codescan->solution->actionList['enable']['notLoadModel'] = true;
$config->codescan->solution->actionList['enable']['showText']     = true;

$config->codescan->solution->actionList['disable']['icon']         = 'cancel';
$config->codescan->solution->actionList['disable']['hint']         = $lang->codescan->disable;
$config->codescan->solution->actionList['disable']['url']          = array('module' => 'codescan', 'method' => 'changeSolutionState', 'params' => 'solutionID={id}&status=2');
$config->codescan->solution->actionList['disable']['data-confirm'] = array('message' => $lang->codescan->notice->confirmDisableSolution, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->solution->actionList['disable']['ajaxSubmit']   = true;
$config->codescan->solution->actionList['disable']['notLoadModel'] = true;
$config->codescan->solution->actionList['disable']['showText']     = true;

$config->codescan->solution->actionList['editsolution']['icon']        = 'edit';
$config->codescan->solution->actionList['editsolution']['hint']        = $lang->codescan->edit;
$config->codescan->solution->actionList['editsolution']['url']         = array('module' => 'codescan', 'method' => 'editSolution', 'params' => 'solutionID={id}');
$config->codescan->solution->actionList['editsolution']['showText']    = true;
$config->codescan->solution->actionList['editsolution']['data-toggle'] = 'modal';

$config->codescan->solution->actionList['deletesolution']['icon']         = 'trash';
$config->codescan->solution->actionList['deletesolution']['hint']         = $lang->codescan->delete;
$config->codescan->solution->actionList['deletesolution']['url']          = array('module' => 'codescan', 'method' => 'deleteSolution', 'params' => 'solutionID={id}');
$config->codescan->solution->actionList['deletesolution']['data-confirm'] = array('message' => $lang->codescan->notice->deleteSolution, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->solution->actionList['deletesolution']['ajaxSubmit']   = true;
$config->codescan->solution->actionList['deletesolution']['showText']     = true;

$config->codescan->solution->search['module']              = 'codeScanSolution';
$config->codescan->solution->search['fields']['name']      = $lang->codescan->name;
$config->codescan->solution->search['fields']['id']        = $lang->idAB;
$config->codescan->solution->search['fields']['status']    = $lang->codescan->status;
$config->codescan->solution->search['fields']['createdBy'] = $lang->codescan->createdBy;
$config->codescan->solution->search['fields']['updatedBy'] = $lang->codescan->updatedBy;

$config->codescan->solution->search['params']['name']      = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->solution->search['params']['id']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->solution->search['params']['status']    = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->statusList);
$config->codescan->solution->search['params']['createdBy'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->codescan->solution->search['params']['createdAt'] = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->codescan->solution->search['params']['updatedBy'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->codescan->solution->search['params']['updatedAt'] = array('operator' => '=', 'control' => 'date', 'values' => '');

$config->codescan->actions->solutionview['mainActions']   = array('linkset');
$config->codescan->actions->solutionview['suffixActions'] = array('editsolution', 'deletesolution');

$config->codescan->plan = new stdclass();
$config->codescan->plan->actionList = array();
$config->codescan->plan->actionList['exec']['icon']         = 'start';
$config->codescan->plan->actionList['exec']['hint']         = $lang->codescan->exec;
$config->codescan->plan->actionList['exec']['url']          = array('module' => 'codescan', 'method' => 'exec', 'params' => 'planID={id}&repoID={repoID}');
$config->codescan->plan->actionList['exec']['showText']     = true;
$config->codescan->plan->actionList['exec']['data-toggle']  = 'modal';

$config->codescan->plan->actionList['task']['icon']        = 'audit';
$config->codescan->plan->actionList['task']['hint']        = $lang->codescan->task;
$config->codescan->plan->actionList['task']['url']         = array('module' => 'codescan', 'method' => 'planView', 'params' => 'planID={id}');
$config->codescan->plan->actionList['task']['showText']    = true;

$config->codescan->plan->actionList['editPlan']['icon']     = 'edit';
$config->codescan->plan->actionList['editPlan']['hint']     = $lang->codescan->edit;
$config->codescan->plan->actionList['editPlan']['url']      = array('module' => 'codescan', 'method' => 'editPlan', 'params' => 'planID={id}&serviceRepoID={repoID}&repoID={repoID}');
$config->codescan->plan->actionList['editPlan']['showText'] = true;

$config->codescan->plan->actionList['deletePlan']['icon']         = 'trash';
$config->codescan->plan->actionList['deletePlan']['hint']         = $lang->codescan->delete;
$config->codescan->plan->actionList['deletePlan']['url']          = array('module' => 'codescan', 'method' => 'deletePlan', 'params' => 'serviceRepoID={repoID}&planID={id}&repoID={repoID}');
$config->codescan->plan->actionList['deletePlan']['data-confirm'] = array('message' => $lang->codescan->notice->deletePlan, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->plan->actionList['deletePlan']['ajaxSubmit']   = true;
$config->codescan->plan->actionList['deletePlan']['showText']     = true;

$planViewActions = array('planview', 'trigger', 'task');
foreach($planViewActions as $action)
{
    $config->codescan->actions->{$action}['mainActions']   = array('exec', 'editPlan', 'deletePlan');
    $config->codescan->actions->{$action}['suffixActions'] = array();
}

$config->codescan->trigger = new stdclass();
$config->codescan->trigger->actionList['editTrigger']['icon']        = 'edit';
$config->codescan->trigger->actionList['editTrigger']['hint']        = $lang->codescan->edit;
$config->codescan->trigger->actionList['editTrigger']['url']         = array('module' => 'codescan', 'method' => 'editTrigger', 'params' => 'triggerID={id}&planID={plan}');
$config->codescan->trigger->actionList['editTrigger']['showText']    = true;
$config->codescan->trigger->actionList['editTrigger']['data-toggle'] = 'modal';

$config->codescan->trigger->actionList['deleteTrigger']['icon']         = 'trash';
$config->codescan->trigger->actionList['deleteTrigger']['hint']         = $lang->codescan->delete;
$config->codescan->trigger->actionList['deleteTrigger']['url']          = array('module' => 'codescan', 'method' => 'deleteTrigger', 'params' => 'triggerID={id}');
$config->codescan->trigger->actionList['deleteTrigger']['data-confirm'] = array('message' => $lang->codescan->notice->deleteTrigger, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->codescan->trigger->actionList['deleteTrigger']['ajaxSubmit']   = true;
$config->codescan->trigger->actionList['deleteTrigger']['showText']     = true;

$config->codescan->task = new stdclass();
$config->codescan->task->actionList['resend']['icon']       = 'restart';
$config->codescan->task->actionList['resend']['hint']       = $lang->codescan->resend;
$config->codescan->task->actionList['resend']['url']        = array('module' => 'codescan', 'method' => 'resend', 'params' => 'taskID={id}');
$config->codescan->task->actionList['resend']['className']  = 'ajax-submit';
$config->codescan->task->actionList['resend']['showText']   = true;

$config->codescan->task->actionList['issue']['icon']        = 'list';
$config->codescan->task->actionList['issue']['hint']        = $lang->codescan->issue;
$config->codescan->task->actionList['issue']['url']         = array('module' => 'codescan', 'method' => 'taskview', 'params' => 'serviceRepoID={repo_id}&taskID={id}&repoID={repo}&type=issue');
$config->codescan->task->actionList['issue']['showText']    = true;

$config->codescan->task->actionList['taskLog']['icon']     = 'file-log';
$config->codescan->task->actionList['taskLog']['hint']     = $lang->codescan->taskLog;
$config->codescan->task->actionList['taskLog']['url']      = array('module' => 'codescan', 'method' => 'taskLog', 'params' => 'taskID={id}');
$config->codescan->task->actionList['taskLog']['showText'] = true;

$config->codescan->task->search['module']             = 'codeScanTask';
$config->codescan->task->search['fields']['id']       = $lang->idAB;
$config->codescan->task->search['fields']['repo']     = $lang->codescan->repo;
$config->codescan->task->search['fields']['branch']   = $lang->codescan->branch;
$config->codescan->task->search['fields']['status']   = $lang->codescan->runStatus;
$config->codescan->task->search['fields']['result']   = $lang->codescan->result;
$config->codescan->task->search['fields']['plan']     = $lang->codescan->scanPlan;
$config->codescan->task->search['fields']['started']  = $lang->codescan->startTime;
$config->codescan->task->search['fields']['finished'] = $lang->codescan->endTime;

$config->codescan->task->search['params']['id']       = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->task->search['params']['repo']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->task->search['params']['branch']   = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->codescan->task->search['params']['status']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->latestExecStatusList);
$config->codescan->task->search['params']['result']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->latestScanResultList);
$config->codescan->task->search['params']['plan']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->task->search['params']['started']  = array('operator' => '>', 'control' => 'datetime', 'values' => '');
$config->codescan->task->search['params']['finished'] = array('operator' => '>', 'control' => 'datetime', 'values' => '');

$config->codescan->issue = new stdclass();

$config->codescan->actions->issueview['suffixActions'] = array();

$config->codescan->issue->search['module'] = 'codeScanIssue';
$config->codescan->issue->search['fields']['issueID']      = $lang->idAB;
$config->codescan->issue->search['fields']['message']      = $lang->codescan->title;
$config->codescan->issue->search['fields']['scanBranch']   = $lang->codescan->scanBranch;
$config->codescan->issue->search['fields']['file']         = $lang->codescan->file;
$config->codescan->issue->search['fields']['rulePriority'] = $lang->codescan->severity;
$config->codescan->issue->search['fields']['type']         = $lang->codescan->type;
$config->codescan->issue->search['fields']['plugin']       = $lang->codescan->tool;
$config->codescan->issue->search['fields']['status']       = $lang->codescan->status;
$config->codescan->issue->search['fields']['createDate']   = $lang->codescan->createTime;
$config->codescan->issue->search['fields']['plan']         = $lang->codescan->scanPlan;
$config->codescan->issue->search['fields']['ruleID']       = $lang->codescan->ruleID;

$config->codescan->issue->search['params']['issueID']      = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->codescan->issue->search['params']['message']      = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->codescan->issue->search['params']['scanBranch']   = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->issue->search['params']['file']         = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->codescan->issue->search['params']['rulePriority'] = array('operator' => '=', 'control' => 'select', 'values' => array_diff_key($lang->codescan->severityList, array_flip(array('all'))));
$config->codescan->issue->search['params']['type']         = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->typeList);
$config->codescan->issue->search['params']['plugin']       = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->issue->search['params']['status']       = array('operator' => '=', 'control' => 'select', 'values' => $lang->codescan->issueStatusList);
$config->codescan->issue->search['params']['createDate']   = array('operator' => '=', 'control' => 'date',   'values' => '');
$config->codescan->issue->search['params']['plan']         = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->codescan->issue->search['params']['ruleID']       = array('operator' => '=', 'control' => 'input',  'values' => '');

$config->codescan->remoteFields = array();
$config->codescan->remoteFields['updatedBy']        = 'editedBy';
$config->codescan->remoteFields['repo']             = 'repoID';
$config->codescan->remoteFields['latestScanTime']   = 'latestTaskCreated';
$config->codescan->remoteFields['latestExecStatus'] = 'latestTaskStatus';
$config->codescan->remoteFields['latestExecResult'] = 'latestTaskResult';
$config->codescan->remoteFields['scanBranch']       = 'branch';
$config->codescan->remoteFields['plan']             = 'planID';

$config->codescan->apiError = array();
$config->codescan->apiError[] = 'failed to create solution, solution already exists';
$config->codescan->apiError[] = 'Internal error occurred';
$config->codescan->apiError[] = '/Failed to update scan plan trigger: invalid (.*) expression: (.*)./';
$config->codescan->apiError[] = 'Failed to delete scan plan trigger: scan plan has only one trigger, cannot delete.';
$config->codescan->apiError[] = 'plan already exists';
$config->codescan->apiError[] = 'failed to create rule set, rule set already exists';
$config->codescan->apiError[] = 'scan ruleset is being used by scan solution';

$config->codescan->createtrigger = new stdclass();
$config->codescan->createtrigger->requiredFields = '';

$config->codescan->edittrigger = new stdclass();
$config->codescan->edittrigger->requiredFields = '';

$config->codescan->severityMapList = array();
$config->codescan->severityMapList['all']    = -1;
$config->codescan->severityMapList['low']    = 0;
$config->codescan->severityMapList['medium'] = 1;
$config->codescan->severityMapList['high']   = 2;
