<?php
$lang->custom->common     = 'Custom';
$lang->custom->index      = 'Home';
$lang->custom->set        = 'Customize';
$lang->custom->restore    = 'Reset';
$lang->custom->key        = 'Key';
$lang->custom->value      = 'Value';
$lang->custom->flow       = 'Concept';
$lang->custom->working    = 'Mode';
$lang->custom->select     = 'Select Concept';
$lang->custom->branch     = 'Multi-Branch';
$lang->custom->owner      = 'Owner';
$lang->custom->module     = 'Module';
$lang->custom->section    = 'Section';
$lang->custom->lang       = 'Language';
$lang->custom->setPublic  = 'Set Public';
$lang->custom->required   = 'Required Field';
$lang->custom->score      = 'Point';
$lang->custom->timezone   = 'Timezone';
$lang->custom->scoreReset = 'Reset Points';
$lang->custom->scoreTitle = 'Point Feature';

$lang->custom->object['story']    = 'Story';
$lang->custom->object['task']     = 'Task';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = 'Case';
$lang->custom->object['testtask'] = 'Build';
$lang->custom->object['todo']     = 'Todo';
$lang->custom->object['user']     = 'User';
$lang->custom->object['block']    = 'Block';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Close Reason';
$lang->custom->story->fields['stageList']        = 'Phase';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewResultList'] = 'Review Result';
$lang->custom->story->fields['review']           = 'Need Review';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = 'Priority';
$lang->custom->task->fields['typeList']   = 'Type';
$lang->custom->task->fields['reasonList'] = 'Close Reason';
$lang->custom->task->fields['statusList'] = 'Status';
$lang->custom->task->fields['hours']      = 'Effort';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = 'Priority';
$lang->custom->bug->fields['severityList']   = 'Severity';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Type';
$lang->custom->bug->fields['resolutionList'] = 'Resolution';
$lang->custom->bug->fields['statusList']     = 'Status';
$lang->custom->bug->fields['longlife']       = 'Stalled Days';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = 'Priority';
$lang->custom->testcase->fields['typeList']   = 'Type';
$lang->custom->testcase->fields['stageList']  = 'Phase';
$lang->custom->testcase->fields['resultList'] = 'Result';
$lang->custom->testcase->fields['statusList'] = 'Status';
$lang->custom->testcase->fields['review']     = 'Need Review';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = 'Priority';
$lang->custom->testtask->fields['statusList'] = 'Status';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Priority';
$lang->custom->todo->fields['typeList']   = 'Type';
$lang->custom->todo->fields['statusList'] = 'Status';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = 'Role';
$lang->custom->user->fields['statusList']   = 'Status';
$lang->custom->user->fields['contactField'] = 'Available Contact';
$lang->custom->user->fields['deleted']      = 'Deleted User';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = 'Closed Block';

$lang->custom->currentLang = 'Current Language';
$lang->custom->allLang     = 'All Languages';

$lang->custom->confirmRestore = 'Do you want to reset?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice   = 'Control whether the above fields are displayed on the user-related page. Leave it blank to display all.';
$lang->custom->notice->canNotAdd         = 'It will be calculated, so customization is not enabled.';
$lang->custom->notice->forceReview       = '%s review is required for committers selected.';
$lang->custom->notice->forceNotReview    = "%s review is not required for committers selected.";
$lang->custom->notice->longlife          = 'Define stalled bugs.';
$lang->custom->notice->invalidNumberKey  = 'The key should be =< 255.';
$lang->custom->notice->invalidStringKey  = 'The key should be lowercase letters, numbers or underlines.';
$lang->custom->notice->cannotSetTimezone = 'date_default_timezone_set does not exist or is disabled. Timezone cannot be set.';
$lang->custom->notice->noClosedBlock     = 'You have no blocks that are closed permanently.';
$lang->custom->notice->required          = 'The selected field is required.';
$lang->custom->notice->conceptResult     = 'According to your preference, <b> %s-%s </b> is set for you. Use <b>%s</b> + <b> %s</b>.';
$lang->custom->notice->conceptPath       = 'Go to Admin -> Custom -> Concept to set it.';

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ has Product Home. Do you want to go to Product Home?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ has Project Home. Do you want to go to Project Home?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ has Test Homepage. Do you want to go to Test Homepage?";

$lang->custom->notice->invalidStrlen['ten']        = 'The key should be <= 10 characters.';
$lang->custom->notice->invalidStrlen['twenty']     = 'The key should be <= 20 characters.';
$lang->custom->notice->invalidStrlen['thirty']     = 'The key should be <= 30 characters.';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'The key should be <= 225 characters.';

$lang->custom->storyReview    = 'Review';
$lang->custom->forceReview    = 'Review Required';
$lang->custom->forceNotReview = 'No Review Required';
$lang->custom->reviewList[1]  = 'On';
$lang->custom->reviewList[0]  = 'Off';

$lang->custom->deletedList[1] = 'Show';
$lang->custom->deletedList[0] = 'Hide';

$lang->custom->workingHours   = 'Hours/Day';
$lang->custom->weekend        = 'Weekend';
$lang->custom->weekendList[2] = '2-Day Off';
$lang->custom->weekendList[1] = '1-Day Off';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'Product - Project';
$lang->custom->productProject->relation['0_1'] = 'Product - Iteration';
$lang->custom->productProject->relation['1_1'] = 'Project - Iteration';
$lang->custom->productProject->relation['0_2'] = 'Product - Sprint';
$lang->custom->productProject->relation['1_2'] = 'Project - Sprint';

$lang->custom->productProject->notice = 'Select a concept that fits your team.';

$lang->custom->workingList['full']      = 'Application Lifecycle Management';
$lang->custom->workingList['onlyTest']  = 'Test Management';
$lang->custom->workingList['onlyStory'] = 'Story Management';
$lang->custom->workingList['onlyTask']  = 'Task Management';

$lang->custom->menuTip  = 'Click to show/hide the menu. Drag to switch display order.';
$lang->custom->saveFail = 'Failed to save!';
$lang->custom->page     = ' Page';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Plan';
$lang->custom->moduleName['project']     = $lang->projectCommon;

$lang->custom->conceptQuestions['overview']         = "1. Which combination of management fits your company?";
$lang->custom->conceptQuestions['story']            = "2. Do you use the concept of requirement or user story in your company?";
$lang->custom->conceptQuestions['requirementpoint'] = "3. Do you use hours or function points to make estimations in your company?";
$lang->custom->conceptQuestions['storypoint']       = "3. Do you use hours or story points to make estimations in your company?";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = 'Requiremenet';
$lang->custom->conceptOptions->story['1'] = 'Story';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Hour';
$lang->custom->conceptOptions->hourPoint['1'] = 'Story Point';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Point';
