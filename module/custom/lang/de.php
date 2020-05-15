<?php
$lang->custom->common     = 'Custom';
$lang->custom->index      = 'Home';
$lang->custom->set        = 'Customize';
$lang->custom->restore    = 'Reset to Default';
$lang->custom->key        = 'Key';
$lang->custom->value      = 'Value';
$lang->custom->flow       = 'Concept';
$lang->custom->working    = 'WorkStyle';
$lang->custom->select     = 'Select Concept';
$lang->custom->branch     = 'Multi Branch';
$lang->custom->owner      = 'Owner';
$lang->custom->module     = 'Module';
$lang->custom->section    = 'Section';
$lang->custom->lang       = 'Language';
$lang->custom->setPublic  = 'Set Public';
$lang->custom->required   = 'Required';
$lang->custom->score      = 'Score';
$lang->custom->timezone   = 'Timezone';
$lang->custom->scoreReset = 'Reset Score';
$lang->custom->scoreTitle = 'Point Feature';

$lang->custom->object['story']    = 'Story';
$lang->custom->object['task']     = 'Task';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = 'Case';
$lang->custom->object['testtask'] = 'Build';
$lang->custom->object['todo']     = 'Todo';
$lang->custom->object['user']     = 'User';
$lang->custom->object['block']    = 'ClosedBlock';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Close Reason';
$lang->custom->story->fields['stageList']        = 'Phase';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewResultList'] = 'Review Result';
$lang->custom->story->fields['review']           = 'Review Required';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = 'Priority';
$lang->custom->task->fields['typeList']   = 'Type';
$lang->custom->task->fields['reasonList'] = 'Close Reason';
$lang->custom->task->fields['statusList'] = 'Status';
$lang->custom->task->fields['hours']      = 'Mannstunden';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = 'Priority';
$lang->custom->bug->fields['severityList']   = 'Severity';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Type';
$lang->custom->bug->fields['resolutionList'] = 'Solution';
$lang->custom->bug->fields['statusList']     = 'Status';
$lang->custom->bug->fields['longlife']       = 'Stalled Days';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = 'Priority';
$lang->custom->testcase->fields['typeList']   = 'Type';
$lang->custom->testcase->fields['stageList']  = 'Phase';
$lang->custom->testcase->fields['resultList'] = 'Result';
$lang->custom->testcase->fields['statusList'] = 'Status';
$lang->custom->testcase->fields['review']     = 'Review Required';

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
$lang->custom->user->fields['deleted']      = 'Show deleted user';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = 'Closed Block';

$lang->custom->currentLang = 'Current Language';
$lang->custom->allLang     = 'All Language';

$lang->custom->confirmRestore = 'Do you want to reset to Default?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice   = 'Control whether the above fields are displayed on the user-related page. Leave it blank to display all.';
$lang->custom->notice->canNotAdd         = 'These items are parameters of calculation, so customized creation is not enabled.';
$lang->custom->notice->forceReview       = '%s Review is required for certain submitters.';
$lang->custom->notice->forceNotReview    = "%s Review is NOT required for certain submitters.";
$lang->custom->notice->longlife          = 'Define shelved bugs.';
$lang->custom->notice->invalidNumberKey  = 'Priority list key should be a natural and not greater than 255.';
$lang->custom->notice->invalidStringKey  = 'The key should be a combination of lowercase English letters, numbers or underscores';
$lang->custom->notice->cannotSetTimezone = 'date_default_timezone_set does not exist or is disabled. Timezone cannot be set.';
$lang->custom->notice->noClosedBlock     = 'You have no blocks that are closed permanently.';
$lang->custom->notice->required          = 'The selected field is required.';
$lang->custom->notice->conceptResult     = 'According to your preference, <b> %s-%s </b> is set for you. Use <b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath       = 'Go to Admin -> Custom -> Concept to set it.';

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ has Product Homepage. Do you want to go to Product Homepage?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ has Project Homepage. Do you want to go to Project Homepage?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ has QA Homepage. Do you want to go to QA Homepage?";

$lang->custom->notice->invalidStrlen['ten']        = 'The length of the key must be less than 10 characters.';
$lang->custom->notice->invalidStrlen['twenty']     = 'The length of the key must be less than 20 characters.';
$lang->custom->notice->invalidStrlen['thirty']     = 'The length of the key must be less than 30 characters.';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'The length of the key must be less than 225 characters.';

$lang->custom->storyReview    = 'Review';
$lang->custom->forceReview    = 'Selective Review';
$lang->custom->forceNotReview = 'No review';
$lang->custom->reviewList[1]  = 'On';
$lang->custom->reviewList[0]  = 'Off';

$lang->custom->deletedList[1] = 'Show';
$lang->custom->deletedList[0] = 'Hide';

$lang->custom->workingHours   = 'hours/day';
$lang->custom->weekend        = 'Weekend';
$lang->custom->weekendList[2] = '2-Day Off';
$lang->custom->weekendList[1] = '1-Day Off';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'Product - Project';
$lang->custom->productProject->relation['0_1'] = 'Product - Iteration';
$lang->custom->productProject->relation['1_1'] = 'Project - Iteration';
$lang->custom->productProject->relation['0_2'] = 'Product - Sprint';
$lang->custom->productProject->relation['1_2'] = 'Project - Sprint';

$lang->custom->productProject->notice = 'Please select a concept according to your team.';

$lang->custom->workingList['full']      = 'Full Management of Dev';
$lang->custom->workingList['onlyTest']  = 'Test Management';
$lang->custom->workingList['onlyStory'] = 'Story Management';
$lang->custom->workingList['onlyTask']  = 'Task Management';

$lang->custom->menuTip  = 'Click to show/hide navigation bar. Drag to swtich display order.';
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
