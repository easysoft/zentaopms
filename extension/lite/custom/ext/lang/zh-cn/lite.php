<?php
$lang->custom->executionCommon = '看板';
$lang->custom->closedExecution = '已关闭' . $lang->custom->executionCommon;
$lang->custom->notice->readOnlyOfExecution = "禁止修改后，已关闭{$lang->custom->executionCommon}下的任务、日志以及关联目标都禁止修改。";

$lang->custom->moduleName['execution'] = $lang->custom->executionCommon;

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required'] = $lang->custom->required;
$lang->custom->task->fields['priList']  = '优先级';
$lang->custom->task->fields['typeList'] = '类型';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['priList']          = '优先级';
$lang->custom->story->fields['reasonList']       = '关闭原因';
$lang->custom->story->fields['statusList']       = '状态';
$lang->custom->story->fields['reviewRules']      = '评审规则';
$lang->custom->story->fields['reviewResultList'] = '评审结果';
$lang->custom->story->fields['review']           = '评审流程';

$lang->custom->system = array('required');
