<?php
$lang->custom->execution       = '看板';
$lang->custom->closedExecution = '已关闭' . $lang->custom->execution;
$lang->custom->notice->readOnlyOfExecution = "禁止修改后，已关闭{$lang->custom->execution}下的卡片、日志以及关联目标都禁止修改。";

$lang->custom->moduleName['execution']   = $lang->custom->execution;

$lang->custom->object = array();
$lang->custom->object['project']   = '项目';
$lang->custom->object['execution'] = $lang->custom->execution;
$lang->custom->object['story']     = $lang->SRCommon;
$lang->custom->object['task']      = '卡片';
$lang->custom->object['todo']      = '待办';
$lang->custom->object['user']      = '用户';
$lang->custom->object['block']     = '区块';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = '优先级';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = '优先级';
$lang->custom->story->fields['reasonList']       = '关闭原因';
$lang->custom->story->fields['statusList']       = '状态';
$lang->custom->story->fields['reviewRules']      = '评审规则';
$lang->custom->story->fields['reviewResultList'] = '评审结果';
$lang->custom->story->fields['review']           = '评审流程';

if($this->config->edition != 'open') $lang->custom->system = array('required', 'score');
