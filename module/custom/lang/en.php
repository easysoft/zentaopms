<?php
global $config;

$lang->custom->common               = 'Custom';
$lang->custom->id                   = 'ID';
$lang->custom->set                  = 'Customize';
$lang->custom->restore              = 'Reset';
$lang->custom->key                  = 'Key';
$lang->custom->value                = 'Value';
$lang->custom->working              = 'Mode';
$lang->custom->hours                = 'Hours';
$lang->custom->select               = 'Select Concept';
$lang->custom->branch               = 'Multi-Branch';
$lang->custom->owner                = 'Owner';
$lang->custom->module               = 'Module';
$lang->custom->section              = 'Section';
$lang->custom->lang                 = 'Language';
$lang->custom->setPublic            = 'Set Public';
$lang->custom->required             = 'Required Field';
$lang->custom->score                = 'Point';
$lang->custom->timezone             = 'Timezone';
$lang->custom->scoreReset           = 'Reset Points';
$lang->custom->scoreTitle           = 'Point Feature';
$lang->custom->productName          = $lang->productCommon;
$lang->custom->convertFactor        = 'Convert factor';
$lang->custom->region               = 'Interval';
$lang->custom->tips                 = 'Tips';
$lang->custom->setTips              = 'Set Tips';
$lang->custom->isRange              = 'Is Target Control';
$lang->custom->concept              = "Concept";
$lang->custom->URStory              = "User requirements";
$lang->custom->SRStory              = "Software requirements";
$lang->custom->epic                 = "Epic";
$lang->custom->default              = "Default";
$lang->custom->scrumStory           = "Story";
$lang->custom->waterfallCommon      = "Waterfall";
$lang->custom->buildin              = "Buildin";
$lang->custom->editStoryConcept     = "Edit Story Concept";
$lang->custom->setStoryConcept      = "Set Story Concept";
$lang->custom->setDefaultConcept    = "Set Default Concept";
$lang->custom->browseStoryConcept   = "List of story concepts";
$lang->custom->deleteStoryConcept   = "Delete story Concept";
$lang->custom->URConcept            = "UR Concept";
$lang->custom->SRConcept            = "SR Concept";
$lang->custom->reviewRule           = 'Review Rules';
$lang->custom->switch               = "Switch";
$lang->custom->oneUnit              = "One {$lang->hourCommon}";
$lang->custom->convertRelationTitle = "Please firstly set the conversion factor from {$lang->hourCommon} to %s";
$lang->custom->superReviewers       = "Super Reviewer";
$lang->custom->kanban               = "Kanban";
$lang->custom->allUsers             = 'All Users';
$lang->custom->account              = 'Users';
$lang->custom->role                 = 'Role';
$lang->custom->dept                 = 'Dept';
$lang->custom->code                 = $lang->code;
$lang->custom->setCode              = 'Enable or Disable Code';
$lang->custom->executionCommon      = 'Execution';
$lang->custom->selectDefaultProgram = 'Please select default program';
$lang->custom->defaultProgram       = 'Default program';
$lang->custom->modeManagement       = 'Mode Management';
$lang->custom->percent              = $lang->stage->percent;
$lang->custom->setPercent           = "Enable or Disable {$lang->stage->percent}";
$lang->custom->beginAndEndDate      = 'Begin & End';
$lang->custom->beginAndEndDateRange = 'The Range Of Begin & End';
$lang->custom->limitTaskDateAction  = 'Set start and end date required';

$lang->custom->unitList['efficiency'] = 'Working Hours/';
$lang->custom->unitList['manhour']    = 'Man-hour/';
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

$lang->custom->regionMustNumber    = 'The interval must be a number!';
$lang->custom->tipNotEmpty         = 'The prompt can not be empty!';
$lang->custom->currencyNotEmpty    = 'You have to select one currency at least.';
$lang->custom->defaultNotEmpty     = 'The default currency can not be empty';
$lang->custom->convertRelationTips = "After converting {$lang->hourCommon} to %s, the historical data will be converted uniformly to %s.";
$lang->custom->saveTips            = 'After clicking save, the current %s will be used as the default estimation unit';

$lang->custom->numberError = 'The interval must be greater than zero!';

$lang->custom->closedExecution = 'Closed ' . $lang->executionCommon;
$lang->custom->closedKanban    = 'Closed ' . $lang->custom->kanban;
$lang->custom->closedProduct   = 'Closed ' . $lang->productCommon;

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = 'Closed Block';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = 'Currency Setting';
$lang->custom->project->defaultCurrency    = 'Default Currency';
$lang->custom->project->fields['required'] = $lang->custom->required;
$lang->custom->project->fields['unitList'] = 'Unit List';

$lang->custom->execution = new stdClass();
$lang->custom->execution->fields['required']  = $lang->custom->required;
$lang->custom->execution->fields['execution'] = 'Close Setting';

$lang->custom->product = new stdClass();
$lang->custom->product->fields['required']           = $lang->custom->required;
$lang->custom->product->fields['browsestoryconcept'] = 'Story Concpet';
$lang->custom->product->fields['product']            = 'Close Setting';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['categoryList']     = 'Category';
$lang->custom->story->fields['priList']          = 'Priority';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Close Reason';
$lang->custom->story->fields['stageList']        = 'Phase';
$lang->custom->story->fields['statusList']       = 'Status';
$lang->custom->story->fields['reviewRules']      = 'Review Rules';
$lang->custom->story->fields['reviewResultList'] = 'Review Result';
$lang->custom->story->fields['review']           = 'Need Review';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required']      = $lang->custom->required;
$lang->custom->task->fields['priList']       = 'Priority';
$lang->custom->task->fields['typeList']      = 'Type';
$lang->custom->task->fields['reasonList']    = 'Close Reason';
$lang->custom->task->fields['statusList']    = 'Status';
$lang->custom->task->fields['limitTaskDate'] = 'Begin & End';

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
$lang->custom->testcase->fields['review']     = 'Need Review';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['required']   = $lang->custom->required;
$lang->custom->testtask->fields['statusList'] = 'Status';
$lang->custom->testtask->fields['typeList']   = 'Type';
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
$lang->custom->user->fields['contactField'] = 'Available Contact';
$lang->custom->user->fields['deleted']      = 'Deleted User';

$lang->custom->currentLang = 'Current Language';
$lang->custom->allLang     = 'All Languages';

$lang->custom->confirmRestore = 'Do you want to reset?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = 'Control whether the above fields are displayed on the user-related page. Leave it blank to display all.';
$lang->custom->notice->canNotAdd           = 'It will be calculated, so customization is not enabled.';
$lang->custom->notice->forceReview         = '%s review is required for committers selected.';
$lang->custom->notice->forceNotReview      = "%s review is not required for committers selected.";
$lang->custom->notice->longlife            = 'Define stalled bugs.';
$lang->custom->notice->invalidNumberKey    = 'The key should be =< 255.';
$lang->custom->notice->invalidStringKey    = 'The key should be letters, numbers or underlines.';
$lang->custom->notice->cannotSetTimezone   = 'date_default_timezone_set does not exist or is disabled. Timezone cannot be set.';
$lang->custom->notice->noClosedBlock       = 'You have no blocks that are closed permanently.';
$lang->custom->notice->required            = 'The selected field is required.';
$lang->custom->notice->conceptResult       = 'According to your preference, <b> %s-%s </b> is set for you. Use <b>%s</b> + <b> %s</b>.';
$lang->custom->notice->conceptPath         = 'Go to Admin -> Custom -> Concept to set it.';
$lang->custom->notice->readOnlyOfProduct   = 'If Change Forbidden, any change on stories, bugs, cases, efforts, releases, plans and builds of the closed product is also forbidden.';
$lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts and stories of the closed {$lang->executionCommon} is also forbidden.";
$lang->custom->notice->readOnlyOfKanban    = "If Change Forbidden, any change on kanban card and related operations of {$lang->custom->kanban} is also forbidden.";
$lang->custom->notice->URSREmpty           = 'Custom requirement name can not be empty!';
$lang->custom->notice->valueEmpty          = 'Value can not be empty!';
$lang->custom->notice->confirmDelete       = 'Are you sure you want to delete it?';
$lang->custom->notice->confirmReviewCase   = 'Set the case in Wait to Normal?';
$lang->custom->notice->storyReviewTip      = 'After selecting by individual, position, and department, take the union of these three filters. ';
$lang->custom->notice->selectAllTip        = 'After selecting all people, the reviewers will be emptied and grayed out while hiding their positions and departments.';
$lang->custom->notice->repeatKey           = 'Repeat Key %s';
$lang->custom->notice->readOnlyOfCode      = "A code is a management term that exists for secrecy or as an antonym. When code management is enabled, the code information of {$lang->productCommon}, {$lang->projectCommon}, and execution in the system will be displayed in the creation, editing, detail, and list pages.";
$lang->custom->notice->readOnlyOfPercent   = "The \"Workload Ratio\" is used to divide the workload of a {$lang->projectCommon} into different stages. The sum of the percentages of the same level stages cannot exceed 100%. After enabling the \"Workload Ratio\", users have to fill in the ratio fields when setting up the stages in the Waterfall {$lang->projectCommon} and Waterfall Plus {$lang->projectCommon} management models.";

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ has Product Home. Do you want to go to Product Home?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ has {$lang->projectCommon} Home. Do you want to go to {$lang->projectCommon} Home?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ has Test Homepage. Do you want to go to Test Homepage?";

$lang->custom->notice->invalidStrlen['ten']        = 'The key should be <= 10 characters.';
$lang->custom->notice->invalidStrlen['fifteen']    = 'The key should be <= 15 characters.';
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

$lang->custom->setHours       = 'Setting Hours';
$lang->custom->setWeekend     = 'Setting Weekend';
$lang->custom->setHoliday     = 'Setting Holiday';
$lang->custom->workingHours   = 'Hours/Day';
$lang->custom->weekendRole    = 'Role';
$lang->custom->weekendList[1] = '1-Day Off';
$lang->custom->weekendList[2] = '2-Day Off';
$lang->custom->restDayList[6] = 'Saturday rest';
$lang->custom->restDayList[0] = 'Sunday rest';

global $config;
$lang->custom->sprintConceptList[0] = 'Project Product Iteration';
$lang->custom->sprintConceptList[1] = 'Project Product Sprint';

$lang->custom->workingList['full'] = 'Application Lifecycle Management';

$lang->custom->menuTip           = 'Click to show/hide the menu. Drag to switch display order.';
$lang->custom->saveFail          = 'Failed to save!';
$lang->custom->page              = ' Page';
$lang->custom->usage             = 'Usage scenarios';
$lang->custom->selectUsage       = 'Select a scenario';
$lang->custom->useLight          = 'Use Light Mode';
$lang->custom->useALM            = 'Use ALM Mode';
$lang->custom->currentModeTips   = 'You are currently using %s, you can switch to the %s.';
$lang->custom->changeModeTips    = 'Please double confirm to switch to %s Mode.';
$lang->custom->selectProgramTips = "After switching to the Light Mode, in order to ensure the consistency of the data structure, you need to select a program as the default program, and subsequent new {$lang->productCommon} and {$lang->projectCommon} data are associated with this default program.";

$lang->custom->modeList['light'] = 'Light Mode';
$lang->custom->modeList['ALM']   = 'ALM Mode';
$lang->custom->modeList['PLM']   = 'IPD Mode';

$lang->custom->modeIntroductionList['light'] = "Provides the core function of {$lang->projectCommon} management, suitable for small R&D teams";
$lang->custom->modeIntroductionList['ALM']   = 'The concept is more complete and rigorous, and the function is more abundant. It is suitable for medium and large R&D teams';

$lang->custom->features['program']              = 'Program';
$lang->custom->features['productRR']            = $lang->productCommon . ' - R&D Requirements';
$lang->custom->features['productUR']            = $lang->productCommon . ' - User Requirements';
$lang->custom->features['productLine']          = $lang->productCommon . ' - Product Line';
$lang->custom->features['projectScrum']         = $lang->projectCommon . ' - Scrum Model';
$lang->custom->features['projectWaterfall']     = $lang->projectCommon . ' - Waterfall Model';
$lang->custom->features['projectKanban']        = $lang->projectCommon . ' - Kanban Model';
$lang->custom->features['projectAgileplus']     = $lang->projectCommon . ' - Agile + Model';
$lang->custom->features['projectWaterfallplus'] = $lang->projectCommon . ' - Waterfall + Model';
$lang->custom->features['execution']            = 'Execution';
$lang->custom->features['qa']                   = 'QA';
$lang->custom->features['devops']               = 'DevOps';
$lang->custom->features['kanban']               = 'Kanban';
$lang->custom->features['doc']                  = 'Doc';
$lang->custom->features['report']               = $lang->report->common;
$lang->custom->features['system']               = 'System';
$lang->custom->features['assetlib']             = 'Asset Lib';
$lang->custom->features['oa']                   = 'Attend';
$lang->custom->features['ops']                  = 'Deploy';
$lang->custom->features['feedback']             = 'Feedback';
$lang->custom->features['traincourse']          = 'Academy';
$lang->custom->features['workflow']             = 'Workflow';
$lang->custom->features['admin']                = 'Admin';
$lang->custom->features['vision']               = 'Full Feature Interface, Operation Management Interface';

$lang->custom->needClosedFunctions['waterfall']     = 'Waterfall';
$lang->custom->needClosedFunctions['waterfallplus'] = 'Waterfall +';
$lang->custom->needClosedFunctions['URStory']       = 'User Story';
if($config->edition == 'max') $lang->custom->needClosedFunctions['assetLib'] = 'Assetlib';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->CRProduct[1] = 'Change Allowed';
$lang->custom->CRProduct[0] = 'Change Forbidden';

$lang->custom->CRExecution[1] = 'Change Allowed';
$lang->custom->CRExecution[0] = 'Change Forbidden';

$lang->custom->CRKanban[1] = 'Change Allowed';
$lang->custom->CRKanban[0] = 'Change Forbidden';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Plan';
$lang->custom->moduleName['execution']   = $lang->custom->executionCommon;

$lang->custom->conceptQuestions['overview']   = "Which combination of management fits your company?";
$lang->custom->conceptQuestions['URAndSR']    = "Do you want to use the concept of {$lang->URCommon} and {$lang->SRCommon} in ZenTao?";
$lang->custom->conceptQuestions['storypoint'] = "Which of the following units is your company using for scale estimation?";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = 'Requirement';
$lang->custom->conceptOptions->story['1'] = 'Story';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = 'Yes';
$lang->custom->conceptOptions->URAndSR['0'] = 'No';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Hours';
$lang->custom->conceptOptions->hourPoint['1'] = 'Story Point';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Point';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = 'Set concept';

$lang->custom->reviewRules['allpass']  = 'All passed';
$lang->custom->reviewRules['halfpass'] = 'More than half passed';

$lang->custom->limitTaskDate['0'] = 'Unlimited';
$lang->custom->limitTaskDate['1'] = 'Limited to the execution begin and end date range';
