<?php
$lang->custom->common    = 'Custom';
$lang->custom->index     = 'Home';
$lang->custom->set       = 'Customize';
$lang->custom->restore   = 'Reset to Default';
$lang->custom->key       = 'Key';
$lang->custom->value     = 'Value';
$lang->custom->flow      = 'Process';
$lang->custom->working   = 'Working';
$lang->custom->select    = 'Select Process';
$lang->custom->branch    = 'Multi Branch';

$lang->custom->object['story']    = 'Story';
$lang->custom->object['task']     = 'Task';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = 'Case';
$lang->custom->object['testtask'] = 'Build';
$lang->custom->object['todo']     = 'To-Dos';
$lang->custom->object['user']     = 'User';
$lang->custom->object['block']    = 'Block';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Reason';
$lang->custom->story->fields['stageList']        = 'Stage';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewResultList'] = 'Result';
$lang->custom->story->fields['review']           = 'Review';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = 'Priority';
$lang->custom->task->fields['typeList']   = 'Type';
$lang->custom->task->fields['reasonList'] = 'Reason';
$lang->custom->task->fields['statusList'] = 'Status';
$lang->custom->task->fields['hours']      = 'Working hours';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = 'Priority';
$lang->custom->bug->fields['severityList']   = 'Priority';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Type';
$lang->custom->bug->fields['resolutionList'] = 'Solution';
$lang->custom->bug->fields['statusList']     = 'Status';
$lang->custom->bug->fields['longlife']       = 'Long Life Days';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = 'Priority';
$lang->custom->testcase->fields['typeList']   = 'Type';
$lang->custom->testcase->fields['stageList']  = 'Stage';
$lang->custom->testcase->fields['resultList'] = 'Result';
$lang->custom->testcase->fields['statusList'] = 'Status';
$lang->custom->testcase->fields['review']     = 'Review';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = 'Priority';
$lang->custom->testtask->fields['statusList'] = 'Status';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Priority';
$lang->custom->todo->fields['typeList']   = 'Type';
$lang->custom->todo->fields['statusList'] = 'Status';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']    = 'Role';
$lang->custom->user->fields['statusList']  = 'Status';

$lang->custom->block->fields['closed'] = 'Closed Block';

$lang->custom->currentLang = 'Current Language';
$lang->custom->allLang     = 'All Language';

$lang->custom->confirmRestore = 'Do you want to reset to Default?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userRole             = 'Key must be no more than 20 characters!';
$lang->custom->notice->canNotAdd            = 'This item will be processed, so customized feature is not enabled.';
$lang->custom->notice->forceReview          = 'Review is required for %s submitted by certain assignee.';
$lang->custom->notice->longlife             = 'List "Undone" bugs that are older than hold days from "Longlife" bugs.';
$lang->custom->notice->priListKey           = 'Priority list key should be numbers！';
$lang->custom->notice->severityListKey      = 'Bug severity list key should be numbers.';
$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ has Product Homepage. Do you want to enter Product Homepage?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ has Project Homepage. Do you want to enter Project Homepage?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ has Testing Homepage. Do you want to enter Testing Homepage?";

$lang->custom->storyReview   = 'Review';
$lang->custom->forceReview   = 'Review Required';
$lang->custom->reviewList[1] = 'On';
$lang->custom->reviewList[0] = 'Off';

$lang->custom->workingHours   = 'Man-Hour/Day';
$lang->custom->weekend        = 'Weekend';
$lang->custom->weekendList[2] = '2-Day Off';
$lang->custom->weekendList[1] = '1-Day Off';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'Product - Project';
$lang->custom->productProject->relation['0_1'] = 'Product - Sprint';
$lang->custom->productProject->relation['1_1'] = 'Project - Sprint';

$lang->custom->productProject->notice = 'Please select according to your team.';

$lang->custom->workingList['full']      = 'Full';
$lang->custom->workingList['onlyTest']  = 'Test';
$lang->custom->workingList['onlyStory'] = 'Story';
$lang->custom->workingList['onlyTask']  = 'Task';

$lang->custom->menuTip  = 'Click to show/hide navigation bar. Drag to swtich display order.';
$lang->custom->saveFail = 'Failed to save!';
