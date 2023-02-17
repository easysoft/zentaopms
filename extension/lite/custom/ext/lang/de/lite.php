<?php
$lang->custom->executionCommon = 'Kanban';
$lang->custom->closedExecution = 'Closed ' . $lang->custom->executionCommon;
$lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts and stories of the closed {$lang->executionCommon} is also forbidden.";

$lang->custom->moduleName['execution'] = $lang->custom->executionCommon;

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required'] = $lang->custom->required;
$lang->custom->task->fields['priList']  = 'Priority';
$lang->custom->task->fields['typeList'] = 'Type';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['reasonList']       = 'Close Reason';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewRules']      = 'Review Rules';
$lang->custom->story->fields['reviewResultList'] = 'Review Result';
$lang->custom->story->fields['review']           = 'Need Review';

$lang->custom->system = array('required');
