<?php
global $config;

$lang->custom->common               = 'Custom';
$lang->custom->id                   = 'ID';
$lang->custom->set                  = 'Custom Settings';
$lang->custom->restore              = 'Reset';
$lang->custom->key                  = 'Key';
$lang->custom->value                = 'Value';
$lang->custom->working              = 'Mode';
$lang->custom->hours                = 'Working Hours';
$lang->custom->select               = 'Please select a process: ';
$lang->custom->branch               = 'Multi-Branch';
$lang->custom->owner                = 'Owner';
$lang->custom->module               = 'Module';
$lang->custom->section              = 'Additional Section';
$lang->custom->lang                 = 'Language';
$lang->custom->setPublic            = 'Set asa Public';
$lang->custom->required             = 'Required Field';
$lang->custom->score                = 'Points';
$lang->custom->timezone             = 'Timezone';
$lang->custom->scoreReset           = 'Reset Points';
$lang->custom->scoreTitle           = 'Points Feature';
$lang->custom->productName          = $lang->productCommon;
$lang->custom->convertFactor        = 'Conversion Factor';
$lang->custom->region               = 'Range';
$lang->custom->tips                 = 'Prompt: ';
$lang->custom->setTips              = 'Set Prompt Text';
$lang->custom->isRange              = 'Target Control Scope';
$lang->custom->concept              = "{$lang->projectCommon} Concept";
$lang->custom->URStory              = "Feature";
$lang->custom->SRStory              = "Story";
$lang->custom->default              = "Default";
$lang->custom->scrumStory           = "Story";
$lang->custom->waterfallCommon      = "Waterfall";
$lang->custom->buildin              = "Built-in";
$lang->custom->editStoryConcept     = "Edit Story Concept";
$lang->custom->setStoryConcept      = "Set Story Concept";
$lang->custom->setDefaultConcept    = "Set Default Concept";
$lang->custom->browseStoryConcept   = "Story Concept List";
$lang->custom->deleteStoryConcept   = "Delete story Concept";
$lang->custom->ERConcept            = "Epic Concept";
$lang->custom->URConcept            = "Feature Concept";
$lang->custom->SRConcept            = "Story Concept";
$lang->custom->reviewRule           = 'Review Rules';
$lang->custom->switch               = "Switch";
$lang->custom->oneUnit              = "One {$lang->hourCommon}";
$lang->custom->convertRelationTitle = "Please set the conversion factor for converting {$lang->hourCommon} to %s.";
$lang->custom->superReviewers       = "Super Reviewer";
$lang->custom->kanban               = "Kanban";
$lang->custom->allUsers             = 'All Users';
$lang->custom->account              = 'Users';
$lang->custom->role                 = 'Role';
$lang->custom->dept                 = 'Dept';
$lang->custom->code                 = $lang->code;
$lang->custom->setCode              = 'Enable Code';
$lang->custom->projectCommon        = $lang->projectCommon . ' Settings';
$lang->custom->executionCommon      = 'Execution';
$lang->custom->selectDefaultProgram = 'Please select a default program.';
$lang->custom->defaultProgram       = 'Default program';
$lang->custom->modeManagement       = 'Mode Management';
$lang->custom->percent              = $lang->stage->percent;
$lang->custom->setPercent           = "Enable {$lang->stage->percent}";
$lang->custom->beginAndEndDate      = 'Duration';
$lang->custom->beginAndEndDateRange = 'Duration Range';
$lang->custom->limitTaskDateAction  = 'Set Duration Required';
$lang->custom->closeSetting         = 'Close Settings';
$lang->custom->gradeRule            = 'Allow Cross-Level Subdivision';
$lang->custom->setExecutionClose    = 'Execution Close Setting';
$lang->custom->kanbanExpireReminder = 'Kanban Expire Reminder';
$lang->custom->kanbanExpireDays     = 'Kanban Expire Days';

$lang->custom->gradeRuleList['cross']    = 'Yes';
$lang->custom->gradeRuleList['stepwise'] = 'No';

$lang->custom->unitList['efficiency'] = 'Working Hours/';
$lang->custom->unitList['manhour']    = 'Man Hours/';
$lang->custom->unitList['cost']       = 'USD/Hour';
$lang->custom->unitList['hours']      = 'Hours';
$lang->custom->unitList['days']       = 'Days';
$lang->custom->unitList['loc']        = 'KLOC';

$lang->custom->tipProgressList['SPI'] = 'Schedule Performance Index(SPI)';
$lang->custom->tipProgressList['SV']  = 'Schedule Variance(SV%)';

$lang->custom->tipCostList['CPI'] = 'Cost Performed Index(CPI)';
$lang->custom->tipCostList['CV']  = 'Cost Variance(CV%)';

$lang->custom->tipRangeList[0]  = 'No';
$lang->custom->tipRangeList[1]  = 'Yes';

$lang->custom->regionMustNumber    = 'The range must be a number.';
$lang->custom->tipNotEmpty         = 'The prompt text cannot be empty.';
$lang->custom->currencyNotEmpty    = 'Select at least one currency.';
$lang->custom->defaultNotEmpty     = 'The default currency cannot be empty.';
$lang->custom->convertRelationTips = "After converting {Slang->hourCommon} to %s, all historical data will be converted to %s.";
$lang->custom->saveTips            = 'After clicking Save, the current %s will become the default estimation unit.';

$lang->custom->numberError = 'The range must be greater than zero.';
$lang->custom->hoursError  = 'Available working hours must be between 0 and 24.';

$lang->custom->closedProject   = 'Closed ' . $lang->projectCommon;
$lang->custom->closedExecution = 'Closed ' . $lang->executionCommon;
$lang->custom->closedKanban    = 'Closed ' . $lang->custom->kanban;
$lang->custom->closedProduct   = 'Closed ' . $lang->productCommon;

$lang->custom->gradeStatusList['enable']  = 'Normal';
$lang->custom->gradeStatusList['disable'] = 'Disabled';

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = 'Closed Blocks';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = 'Currency Settings';
$lang->custom->project->defaultCurrency    = 'Default Currency';
$lang->custom->project->fields['required'] = $lang->custom->required;
$lang->custom->project->fields['project']  = 'Close Settings';
$lang->custom->project->fields['unitList'] = 'Budget Unit';

$lang->custom->execution = new stdClass();
$lang->custom->execution->fields['required']  = $lang->custom->required;
$lang->custom->execution->fields['execution'] = 'Close Settings';

$lang->custom->product = new stdClass();
$lang->custom->product->fields['required']           = $lang->custom->required;
$lang->custom->product->fields['browsestoryconcept'] = 'Story Concept';
$lang->custom->product->fields['product']            = 'Close Settings';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['categoryList']     = 'Type';
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Closure Reason';
$lang->custom->story->fields['stageList']        = 'Development Phase';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewRules']      = 'Review Rules';
$lang->custom->story->fields['reviewResultList'] = 'Review Result';
$lang->custom->story->fields['review']           = 'Review Process';

$lang->custom->epic        = clone $lang->custom->story;
$lang->custom->requirement = clone $lang->custom->story;

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required']      = $lang->custom->required;
$lang->custom->task->fields['priList']       = 'Priority';
$lang->custom->task->fields['typeList']      = 'Type';
$lang->custom->task->fields['reasonList']    = 'Closure Reason';
$lang->custom->task->fields['statusList']    = 'Status';
$lang->custom->task->fields['limitTaskDate'] = 'Duration';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['required']       = $lang->custom->required;
$lang->custom->bug->fields['priList']        = 'Priority';
$lang->custom->bug->fields['severityList']   = 'Severity';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Type';
$lang->custom->bug->fields['resolutionList'] = 'Resolution';
$lang->custom->bug->fields['statusList']     = 'Status';
$lang->custom->bug->fields['longlife']       = 'Stalled Days';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['required']   = $lang->custom->required;
$lang->custom->testcase->fields['priList']    = 'Priority';
$lang->custom->testcase->fields['typeList']   = 'Type';
$lang->custom->testcase->fields['stageList']  = 'Phase';
$lang->custom->testcase->fields['resultList'] = 'Result';
$lang->custom->testcase->fields['statusList'] = 'Status';
$lang->custom->testcase->fields['review']     = 'Review Process';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['required']   = $lang->custom->required;
$lang->custom->testtask->fields['statusList'] = 'Status';
$lang->custom->testtask->fields['typeList']   = 'Test Type';
$lang->custom->testtask->fields['priList']    = 'Priority';

$lang->custom->testreport = new stdClass();
$lang->custom->testreport->fields['required'] = $lang->custom->required;

$lang->custom->caselib = new stdClass();
$lang->custom->caselib->fields['required'] = $lang->custom->required;

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Priority';
$lang->custom->todo->fields['typeList']   = 'Type';
$lang->custom->todo->fields['statusList'] = 'Status';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['required']     = $lang->custom->required;
$lang->custom->user->fields['roleList']     = 'Role';
$lang->custom->user->fields['statusList']   = 'Status';
$lang->custom->user->fields['contactField'] = 'Available Contact Methods';
$lang->custom->user->fields['deleted']      = 'Show Deleted Users';

$lang->custom->currentLang = 'Apply to Current Language';
$lang->custom->allLang     = 'Apply to All Languages';

$lang->custom->confirmRestore = 'Are you sure you want to restore default settings?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = 'Controls the visibility of the above fields on user-related pages. Leave blank to show all.';
$lang->custom->notice->canNotAdd           = 'This item is used in calculations; custom additions are not available.';
$lang->custom->notice->forceReview         = '%s submitted by specified users require review.';
$lang->custom->notice->forceNotReview      = "%s submitted by specified users do not require review.";
$lang->custom->notice->longlife            = 'The Stalled tab on the Bug List page displays bugs that have been unresolved for longer than the configured number of days.';
$lang->custom->notice->invalidNumberKey    = 'The key value must be a number no greater than 255.';
$lang->custom->notice->invalidStringKey    = 'The key value must be a combination of uppercase/lowercase letters, numbers, or underscores.';
$lang->custom->notice->cannotSetTimezone   = 'The function \'date_default_timezone_set\' is missing or disabled; the timezone cannot be set.';
$lang->custom->notice->noClosedBlock       = 'No permanently closed blocks.';
$lang->custom->notice->required            = 'Selected fields are mandatory upon form submission.';
$lang->custom->notice->conceptResult       = 'Based on your selection, we have configured the <b>%s-%s</b> mode using <b>%s</b> + <b>%s</b>.';
$lang->custom->notice->conceptPath         = 'Go to Admin -> Custom -> Concept to set it.';
$lang->custom->notice->readOnlyOfProduct   = "Once set Changes Prohibited, items under a closed {$lang->SRCommon}, Bug, Test Case, Effort, Release, Plan, and Build cannot be changed.";
$lang->custom->notice->readOnlyOfProject   = "Once set Changes Prohibited, any change on closed {$lang->projectCommon}s is not allowed:<br/>
1. For {$lang->productCommon}-based {$lang->projectCommon}s with {$lang->custom->executionCommon} enabled: The following will not be editable under closed {$lang->projectCommon}s: {$lang->custom->executionCommon}, stories, design, reviews, review issues, baselines, documents, builds, releases, efforts, test requests, test reports, process tailoring, research, estimation, issues, risks, opportunities, meetings, QA plans, non-conformities, etc.<br/>
2. For {$lang->productCommon}-based {$lang->projectCommon}s with{$lang->custom->executionCommon} disabled: The following will not be editable under closed {$lang->projectCommon}s: tasks, stories, builds, releases, efforts, test request, test reports, documents, etc.<br/>
3. For non-{$lang->productCommon}-based {$lang->projectCommon}s with {$lang->custom->executionCommon} enabled: The following will not be editable under closed {$lang->projectCommon}s: {$lang->custom->executionCommon}, stories, design, reviews, review issues, baselines, bugs, test cases, test requestd, test reports, documents, builds, releases, efforts, process tailoring, research, estimation, issues, risks, opportunities, meetings, QA plans, non-conformities, etc.<br/>
4. For non-{$lang->productCommon}-based {$lang->projectCommon}s with {$lang->custom->executionCommon} disabled: The following will not be editable under closed {$lang->projectCommon}s: tasks, stories, bugs, test cases, test requestd, test reports, documents, builds, releases, efforts, etc.";
$lang->custom->notice->kanbanReminder      = 'When the card deadline is less than the set number of days, the expiration reminder is triggered.';
if(in_array($config->edition, array('open', 'biz')))
{
    $lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts, test tasks, test reports, documents and stories of the closed {$lang->executionCommon} is also forbidden.";
}
else
{
    $lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts, test tasks, test reports, documents, issues, risks, QAs, meettings and stories of the closed {$lang->executionCommon} is also forbidden.";
}
$lang->custom->notice->readOnlyOfKanban    = "If Change Forbidden, any change on kanban card and related operations of {$lang->custom->kanban} is also forbidden.";
$lang->custom->notice->URSREmpty           = 'The custom story name cannot be empty.';
$lang->custom->notice->valueEmpty          = 'The value cannot be empty.';
$lang->custom->notice->confirmDelete       = 'Are you sure you want to delete it?';
$lang->custom->notice->confirmReviewCase   = 'Do you want to change the status of test cases Waiting Review to Normal?';
$lang->custom->notice->storyReviewTip      = 'When users, roles, or departments are selected, the system takes the union of all included members.';
$lang->custom->notice->selectAllTip        = 'When all users are selected, the reviewers will be cleared and disabled, and both the role and department filters will be hidden.';
$lang->custom->notice->repeatKey           = 'Duplicate Key: %s';
$lang->custom->notice->readOnlyOfCode      = "A code is a management alias used mainly for confidentiality or alternate identification. Once code is enabled, code will appear for ($lang->productCommon), {$lang->projectCommon}, and execution across the create, edit, detail, and list views.";
$lang->custom->notice->readOnlyOfPercent   = "Workload Ratio is used to allocate workloads across multiple phases within a {$lang->projectCommon}. The total percentage among phases of the same level cannot exceed 100%. Once Workload Ratio is enabled, phase workload allocation must be maintained in both Waterfall {$lang->projectCommon} and Waterfall+ {$lang->projectCommon} models.";
$lang->custom->notice->gradeRule           = 'Cross-level breakdown: You can create stories from any hierarchy level and establish parent-child links across layers. For example, you may create a Level 3 story directly under a Level 1 story.';

$lang->custom->notice->indexPage['product'] = "Starting from version 8.2, a Product Home view has been added. Would you like to set it as the default landing page?";
$lang->custom->notice->indexPage['project'] = "Starting from version 8.2, a {$lang->projectCommon} Home view has been added. Would you like to set it as the default landing page?";
$lang->custom->notice->indexPage['qa']      = "Starting from version 8.2, a Test Home view has been added. Would you like to set it as the default landing page?";

$lang->custom->notice->invalidStrlen['ten']        = 'The key should be <= 10 characters.';
$lang->custom->notice->invalidStrlen['fifteen']    = 'The key should be <= 15 characters.';
$lang->custom->notice->invalidStrlen['twenty']     = 'The key should be <= 20 characters.';
$lang->custom->notice->invalidStrlen['thirty']     = 'The key should be <= 30 characters.';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'The key should be <= 225 characters.';

$lang->custom->storyReview    = 'Review Process';
$lang->custom->forceReview    = 'Mandatory Review';
$lang->custom->forceNotReview = 'No Review Required';
$lang->custom->reviewList[1]  = 'Enable';
$lang->custom->reviewList[0]  = 'Disable';

$lang->custom->deletedList[1] = 'Show';
$lang->custom->deletedList[0] = 'Hide';

$lang->custom->setHours       = 'Work Hour Settings';
$lang->custom->setWeekend     = 'Rest Day Settings';
$lang->custom->setHoliday     = 'Holiday Settings';
$lang->custom->workingHours   = 'Available Work Hours per Day';
$lang->custom->weekendRole    = 'Rule Settings';
$lang->custom->weekendList[1] = '1-Day Off';
$lang->custom->weekendList[2] = '2-Days Off';
$lang->custom->restDayList[6] = 'Saturday Off';
$lang->custom->restDayList[0] = 'Sunday Off';

global $config;
$lang->custom->sprintConceptList[0] = 'Project Product Iteration';
$lang->custom->sprintConceptList[1] = 'Project Product Sprint';

$lang->custom->workingList['full'] = 'A Complete R&D Management Tool';

$lang->custom->menuTip           = 'Click to show or hide navigation items, and drag to change their display order.';
$lang->custom->saveFail          = 'Failed to save.';
$lang->custom->page              = ' Page';
$lang->custom->usage             = 'Usage scenarios';
$lang->custom->selectUsage       = 'Select a scenario';
$lang->custom->useLight          = 'Light Mode';
$lang->custom->useALM            = 'ALM Mode';
$lang->custom->currentModeTips   = 'You are currently using %s mode. You can switch to %s mode.';
$lang->custom->changeModeTips    = 'Are you sure you want to switch to %s mode?';
$lang->custom->selectProgramTips = "After switching to the Light Mode, you need to select a program as the default one to maintain data consistency. All newly created {$lang->productCommon} and {$lang->projectCommon} entries will be linked with this default program.";

$lang->custom->modeList['light'] = 'Light Mode';
$lang->custom->modeList['ALM']   = 'ALM Mode';
$lang->custom->modeList['PLM']   = 'IPD Mode';

$lang->custom->modeIntroductionList['light'] = "Provides the core function of {$lang->projectCommon} management. Suitable for small R&D teams.";
$lang->custom->modeIntroductionList['ALM']   = 'The concept is more complete and rigorous, and the function is more abundant. It is suitable for medium and large R&D teams.';

$lang->custom->features['program']              = 'Program';
$lang->custom->features['productRR']            = $lang->productCommon . ' - Story';
$lang->custom->features['productUR']            = $lang->productCommon . ' - Feature';
$lang->custom->features['productER']            = $lang->productCommon . ' - Epic';
$lang->custom->features['productLine']          = $lang->productCommon . ' - Product Line';
$lang->custom->features['projectScrum']         = $lang->projectCommon . ' - Scrum Model';
$lang->custom->features['projectWaterfall']     = $lang->projectCommon . ' - Waterfall Model';
$lang->custom->features['projectKanban']        = $lang->projectCommon . ' - Kanban Model';
$lang->custom->features['projectAgileplus']     = $lang->projectCommon . ' - Agile + Model';
$lang->custom->features['projectWaterfallplus'] = $lang->projectCommon . ' - Waterfall + Model';
$lang->custom->features['execution']            = 'Execution';
$lang->custom->features['qa']                   = 'Test';
$lang->custom->features['devops']               = 'CI&CD';
$lang->custom->features['kanban']               = 'Kanban';
$lang->custom->features['doc']                  = 'Document';
$lang->custom->features['report']               = 'BI';
$lang->custom->features['system']               = 'Company';
$lang->custom->features['assetlib']             = 'Asset';
$lang->custom->features['oa']                   = 'OA';
$lang->custom->features['ops']                  = 'OPS';
$lang->custom->features['feedback']             = 'Feedback';
$lang->custom->features['traincourse']          = 'Academy';
$lang->custom->features['workflow']             = 'Workflow';
$lang->custom->features['admin']                = 'Admin';
$lang->custom->features['vision']               = 'Full Feature Interface, Operation Management Interface';
$lang->custom->features['ai']                   = 'AI';

$lang->custom->needClosedFunctions['waterfall']     = 'Waterfall';
$lang->custom->needClosedFunctions['waterfallplus'] = 'Waterfall +';
$lang->custom->needClosedFunctions['URStory']       = 'Feature';
if($config->edition == 'max') $lang->custom->needClosedFunctions['assetLib'] = 'Assetlib';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->CRProduct[1] = 'Changes Allowed';
$lang->custom->CRProduct[0] = 'Changes Prohibited';

$lang->custom->CRProject[1] = 'Changes Allowed';
$lang->custom->CRProject[0] = 'Changes Prohibited';

$lang->custom->CRExecution[1] = 'Changes Allowed';
$lang->custom->CRExecution[0] = 'Changes Prohibited';

$lang->custom->CRKanban[1] = 'Changes Allowed';
$lang->custom->CRKanban[0] = 'Changes Prohibited';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Plan';
$lang->custom->moduleName['execution']   = $lang->custom->executionCommon;

$lang->custom->conceptQuestions['overview']   = "Which of the following management models best fits your company’s current workflow?";
$lang->custom->conceptQuestions['URAndSR']    = "Would you like to enable the {$lang->URCommon} and {$lang->SRCommon} concepts?";
$lang->custom->conceptQuestions['storypoint'] = "Which unit does your company use for estimation?";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = 'Feature';
$lang->custom->conceptOptions->story['1'] = 'Story';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = 'Yes';
$lang->custom->conceptOptions->URAndSR['0'] = 'No';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Work Hours';
$lang->custom->conceptOptions->hourPoint['1'] = 'Story Points';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Points';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = "Set {$lang->projectCommon} Concept";

$lang->custom->reviewRules['allpass']  = 'Approved by All';
$lang->custom->reviewRules['halfpass'] = 'Approved by Majority';

$lang->custom->limitTaskDate['0'] = 'No Restriction';
$lang->custom->limitTaskDate['1'] = 'Restricted to the execution duration.';

$lang->custom->setDate = new stdClass();
$lang->custom->setDate->fields['hours']   = $lang->custom->setHours;
$lang->custom->setDate->fields['weekend'] = $lang->custom->setWeekend;
$lang->custom->setDate->fields['holiday'] = $lang->custom->setHoliday;
