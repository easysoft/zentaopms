<?php
global $app;
global $config;

/* Actions. */
$lang->project->createGuide         = 'Select Template';
$lang->project->index               = 'Dashboard';
$lang->project->home                = 'Home';
$lang->project->create              = "Create {$lang->projectCommon}";
$lang->project->edit                = 'Edit';
$lang->project->batchEdit           = "Batch Edit {$lang->projectCommon}s";
$lang->project->view                = "{$lang->projectCommon} Overview";
$lang->project->batchEdit           = "Batch Edit {$lang->projectCommon}s";
$lang->project->browse              = $lang->projectCommon;
$lang->project->all                 = 'All';
$lang->project->involved            = "Involved Projects";
$lang->project->start               = 'Start';
$lang->project->finish              = 'Complete';
$lang->project->suspend             = 'On Hold';
$lang->project->delete              = 'Delete';
$lang->project->close               = 'Close';
$lang->project->activate            = 'Activate';
$lang->project->group               = 'Permission List';
$lang->project->createGroup         = 'Create Group';
$lang->project->editGroup           = 'Edit Group';
$lang->project->copyGroup           = 'Copy Group';
$lang->project->manageView          = 'Manage View Permission';
$lang->project->managePriv          = 'Manage Permission';
$lang->project->manageMembers       = 'Manage Team';
$lang->project->export              = 'Export';
$lang->project->addProduct          = "Add {$lang->productCommon}";
$lang->project->manageGroupMember   = 'Manage Group';
$lang->project->moduleSetting       = 'List Settings';
$lang->project->moduleOpen          = 'Program Name';
$lang->project->moduleOpenAction    = 'Program Name Settings';
$lang->project->dynamic             = 'Recents';
$lang->project->execution           = 'Execution';
$lang->project->bug                 = 'Bug List';
$lang->project->testcase            = 'Test Case';
$lang->project->testtask            = 'Test Request';
$lang->project->build               = 'Build';
$lang->project->updateOrder         = 'Sort';
$lang->project->sort                = 'Sort';
$lang->project->whitelist           = "{$lang->projectCommon} Whitelist";
$lang->project->addWhitelist        = "Add Whitelist";
$lang->project->unbindWhitelist     = "Remove Whitelist";
$lang->project->manageProducts      = "Link {$lang->productCommon}s";
$lang->project->manageOtherProducts = "Link Other {$lang->productCommon}s";
$lang->project->manageProductPlan   = "Link {$lang->productCommon}s and Plans";
$lang->project->managePlans         = 'Link Plans';
$lang->project->copyTitle           = "Please select a {$lang->projectCommon} to copy.";
$lang->project->errorSameProducts   = "{$lang->projectCommon} cannot be linked to multiple identical {$lang->productCommon}s.";
$lang->project->errorSameBranches   = "{$lang->projectCommon} cannot be linked to multiple identical branches.";
$lang->project->errorSamePlans      = "{$lang->projectCommon} cannot be linked to multiple identical plans.";
$lang->project->errorNoProducts     = "At least one {$lang->productCommon} must be linked.";
$lang->project->copyNoProject       = "No available {$lang->projectCommon}s to copy.";
$lang->project->searchByName        = "Enter the {$lang->projectCommon} name to search.";
$lang->project->emptyProgram        = "Independent Projects";
$lang->project->deleted             = 'Deleted';
$lang->project->linkedProducts      = "Linked {$lang->productCommon}s";
$lang->project->unlinkedProducts    = "Unlinked {$lang->productCommon}s";
$lang->project->testreport          = 'Test Report';
$lang->project->selectProgram       = 'Program Filter';
$lang->project->teamMember          = 'Team Member';
$lang->project->unlinkMember        = 'Remove Member';
$lang->project->unlinkMemberAction  = 'Remove Team Member';
$lang->project->copyTeamTitle       = "Select a {$lang->projectCommon} team to copy.";
$lang->project->daysGreaterProject  = "Available workdays cannot exceed {$lang->projectCommon} maximum 『%s』";
$lang->project->errorHours          = 'Available working hours per day cannot exceed 24.';
$lang->project->workdaysExceed      = 'Work Dyas cannot exceed『%s』.';
$lang->project->teamMembersCount    = ', the team has a total of %s members.';
$lang->project->allProjects         = "All {$lang->projectCommon}s";
$lang->project->ignore              = 'Ignore';
$lang->project->disableExecution    = "{$lang->projectCommon} with {$lang->executionCommon} disabled";
$lang->project->selectProduct       = "Select {$lang->productCommon}";
$lang->project->manageRepo          = 'Link Repository';
$lang->project->linkedRepo          = 'Linked Repository';
$lang->project->unlinkedRepo        = 'Unlink Repository';
$lang->project->executionCount      = 'Execution Count';
$lang->project->storyType           = 'Story Concept';
$lang->project->storyCount          = 'Story Count';
$lang->project->storyPoints         = 'Story Points';
$lang->project->invested            = 'Invested';
$lang->project->member              = 'Members';
$lang->project->manage              = 'Manage';
$lang->project->market              = 'Target Market';
$lang->project->tips                = 'Note';
$lang->project->afterInfo           = "{$lang->projectCommon} created successfully. Next you can";
$lang->project->setTeam             = 'Manage Team';
$lang->project->linkStory           = 'Link Stories';
$lang->project->createStory         = "Create {$lang->SRCommon}";
$lang->project->createTask          = "Create Task";
$lang->project->createExecutionTip  = 'Create %s';
$lang->project->setDoc              = 'Add Docs';
$lang->project->backToTaskList      = 'Return to Task List';
$lang->project->backToKanban        = 'Return to Kanban';
$lang->project->backToExecutionList = 'Return to Project %s List';
$lang->project->backToProjectList   = 'Return to Project List';
$lang->project->deletedTip          = "Sorry, the {$lang->projectCommon} you are trying to access has been deleted.";
$lang->project->coverExecutionPriv  = "Execution Priv Control";

$lang->project->manDay          = 'Man-Days';
$lang->project->day             = 'Day';
$lang->project->newProduct      = 'New Product';
$lang->project->associatePlan   = 'Link Plan';
$lang->project->tenThousandYuan = '10k';
$lang->project->planDate        = 'Planned Duration';
$lang->project->delayInfo       = '%s days delayed';

/* Fields. */
$lang->project->common             = $lang->projectCommon;
$lang->project->id                 = 'ID';
$lang->project->project            = $lang->projectCommon;
$lang->project->stage              = 'Phase';
$lang->project->model              = 'Management Type';
$lang->project->PM                 = 'Manager';
$lang->project->PO                 = "{$lang->projectCommon} Manager";
$lang->project->QD                 = 'Test Manager';
$lang->project->RD                 = 'Release Manager';
$lang->project->name               = 'Name';
$lang->project->category           = 'Type';
$lang->project->desc               = 'Description';
$lang->project->code               = 'Code';
$lang->project->hasProduct         = "Link to {$lang->productCommon}";
$lang->project->copy               = 'Copy';
$lang->project->begin              = 'Planned Start';
$lang->project->end                = 'Planned End';
$lang->project->status             = 'Status';
$lang->project->subStatus          = 'Sub Status';
$lang->project->type               = 'Type';
$lang->project->lifetime           = "{$lang->projectCommon} Duration";
$lang->project->attribute          = 'Phase Type';
$lang->project->percent            = 'Workload %';
$lang->project->milestone          = 'Milestone';
$lang->project->milestoneReport    = 'Milestone Report';
$lang->project->output             = 'Output';
$lang->project->path               = 'Path';
$lang->project->grade              = 'Hierarchy';
$lang->project->version            = 'Version';
$lang->project->program            = 'Program';
$lang->project->parentVersion      = 'Parent Version';
$lang->project->planDuration       = 'Planned Duration';
$lang->project->realDuration       = 'Actual Duration';
$lang->project->openedVersion      = 'Create Version';
$lang->project->pri                = 'Priority';
$lang->project->openedBy           = 'Creator';
$lang->project->openedDate         = 'Created on';
$lang->project->lastEditedBy       = 'Last Edited by';
$lang->project->lastEditedDate     = 'Last Edited on';
$lang->project->closedBy           = 'Closed by';
$lang->project->closedDate         = 'Closed on';
$lang->project->closedReason       = 'Closure Reason';
$lang->project->canceledBy         = 'Canceled by';
$lang->project->canceledDate       = 'Canceled on';
$lang->project->team               = 'Team';
$lang->project->teamAction         = 'Team List';
$lang->project->order              = 'Sort';
$lang->project->budget             = 'Budget';
$lang->project->budgetUnit         = "(Unit: {$lang->project->tenThousandYuan})";
$lang->project->suspendedDate      = 'Paused on';
$lang->project->vision             = 'Interface';
$lang->project->displayCards       = 'Max cards per column';
$lang->project->fluidBoard         = 'Column Width';
$lang->project->template           = 'Template';
$lang->project->estimate           = 'Estimated Effort';
$lang->project->consume            = 'Cost';
$lang->project->surplus            = 'Remaining';
$lang->project->progress           = ' Progress';
$lang->project->allProgress        = 'Totoal Progress';
$lang->project->weekProgress       = 'This Week Progress';
$lang->project->dateRange          = 'Planned Duration';
$lang->project->to                 = ' to ';
$lang->project->realBeganAB        = 'Actual Start';
$lang->project->realEndAB          = 'Actual End';
$lang->project->realBegan          = 'Actual Start';
$lang->project->realEnd            = 'Actual End';
$lang->project->stageBy            = 'Phase Type';
$lang->project->bygrid             = 'Kanban';
$lang->project->bylist             = 'List';
$lang->project->bycard             = 'Card';
$lang->project->mine               = 'My Involvement';
$lang->project->myProject          = 'My Managed';
$lang->project->other              = 'Others';
$lang->project->acl                = 'Access Control';
$lang->project->setPlanduration    = 'Set Duration';
$lang->project->auth               = 'Permission';
$lang->project->durationEstimation = 'Estimated Workload';
$lang->project->left               = 'Hours Left';
$lang->project->consumed           = 'Hours Cost';
$lang->project->leftStories        = 'Remaining Stories';
$lang->project->leftTasks          = 'Remaining Tasks';
$lang->project->leftBugs           = 'Remaining Bugs';
$lang->project->leftHours          = 'Hours Left';
$lang->project->children           = "Sub-{$lang->projectCommon}";
$lang->project->parent             = 'Parent Program';
$lang->project->allStories         = 'All Stories';
$lang->project->doneStories        = 'Completed Stories';
$lang->project->doneProjects       = 'Completed';
$lang->project->allInput           = 'Total Invested';
$lang->project->weekly             = 'Weekly Report';
$lang->project->pv                 = 'PV';
$lang->project->ev                 = 'EV';
$lang->project->sv                 = 'SV%';
$lang->project->ac                 = 'AC';
$lang->project->cv                 = 'CV%';
$lang->project->pvTitle            = 'Planed Value';
$lang->project->evTitle            = 'Earn Value';
$lang->project->svTitle            = 'Schedule Variance';
$lang->project->acTitle            = 'Actual Cost';
$lang->project->cvTitle            = 'Cost Variance';
$lang->project->teamCount          = 'Headcounts';
$lang->project->teamSumCount       = '%s members in total';
$lang->project->longTime           = 'Long-Term';
$lang->project->future             = 'TBD';
$lang->project->moreProject        = "More {$lang->projectCommon}s";
$lang->project->days               = 'Work Days';
$lang->project->daysUnit           = ' (Unit: Days)';
$lang->project->mailto             = 'Mail to';
$lang->project->etc                = " , etc";
$lang->project->product            = $lang->productCommon;
$lang->project->branch             = 'Platform/Branch';
$lang->project->plan               = 'Plan';
$lang->project->createKanban       = 'Create Kanban';
$lang->project->kanban             = 'Kanban';
$lang->project->moreActions        = 'More Actions';
$lang->project->taskDateLimit      = 'Scheduling Constraints';
$lang->project->isTpl              = 'Set as template';
$lang->project->tplWhiteList       = 'Template White List';
$lang->project->firstEnd           = 'Planned Completion Date at Start';
$lang->project->parallel           = 'Allow parallel';
$lang->project->enabled            = 'Enable phases';
$lang->project->linkType           = 'Link Type';
$lang->project->colWidth           = 'Column Width';
$lang->project->minColWidth        = 'Minimum Column Width';
$lang->project->maxColWidth        = 'Maximum Column Width';

/* Project Category. */
$lang->project->projectTypeList = array();
$lang->project->projectTypeList[1] = "{$lang->productCommon}-based";
$lang->project->projectTypeList[0] = "Non-{$lang->productCommon}-based";

/* Project Kanban. */
$lang->project->typeList = array();
$lang->project->typeList['my']    = "My Managed {$lang->projectCommon}s";
$lang->project->typeList['other'] = "Other {$lang->projectCommon}s";

$lang->project->stageByList['project'] = "Create by {$lang->projectCommon}";
$lang->project->stageByList['product'] = "Create by {$lang->productCommon}";

$lang->project->stageBySwitchList['0'] = 'Disable';
$lang->project->stageBySwitchList['1'] = "Enable";

$lang->project->waitProjects    = "Waiting {$lang->projectCommon}s";
$lang->project->doingProjects   = "Ongoing {$lang->projectCommon}s";
$lang->project->doingExecutions = 'Ongoing Execution (The latest one.)';
$lang->project->closedProjects  = "Closed {$lang->projectCommon}s (Last 2.)";
$lang->project->closedProject   = "Closed {$lang->projectCommon}s";
$lang->project->noProgram       = "Independent {$lang->projectCommon}s";

$lang->project->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$lang->project->changeProgram          = "%s > Change Program";
$lang->project->changeProgramTip       = "After editing the program, the program linked to the {$lang->productCommon} of this {$lang->projectCommon} will also be updated. Please confirm to proceed.";
$lang->project->linkedProjectsTip      = "Linked {$lang->projectCommon}s are as follows";
$lang->project->multiLinkedProductsTip = "The following {$lang->productCommon} linked to this {$lang->projectCommon} are also associated with other {$lang->projectCommon}. Please unlink them before proceeding.";
$lang->project->noticeDivsion          = "The current {$lang->projectCommon} uses a single-phase set. Click [Enable] to switch to multiple phase sets, where each set is linked to one {$lang->productCommon}.";
$lang->project->linkStoryByPlanTips    = "This action will link all {$lang->SRCommon} under the selected plan to this {$lang->projectCommon}.";
$lang->project->createExecution        = "No {$lang->executionCommon} found under this {$lang->projectCommon}. Please create a {$lang->executionCommon} first.";
$lang->project->unlinkExecutionMember  = "The user participated in %s executions such as %s%s. Do you want to remove the user from those executions as well? (The data related to this user will not be deleted.)";
$lang->project->unlinkExecutionMembers = "The team members you are removing are also in the execution team of this {$lang->projectCommon}. Do you want to remove them from the execution team as well?";
$lang->project->productTip             = "After clicking Create {$lang->productCommon}, the current {$lang->projectCommon} will no longer be linked to the selected {$lang->productCommon}.";
$lang->project->noDevStage             = "No R&D-type phase found under this {$lang->projectCommon}, or you don’t have access permission. Build creation is temporarily not supported.";
$lang->project->budgetOverrun          = "The {$lang->projectCommon}'s budget exceeds the remaining budget of the program: <strong id='currency'></strong><strong id='parentBudget'></strong><strong id='budgetUnit'></strong>.";
$lang->project->disabledInputTip       = 'Please cancel %s first.';
$lang->project->linkRepoFailed         = "Failed to link the repository.";
$lang->project->unLinkProductTip       = 'Are you sure you want to unlink from %s? (It will not affect already linked stories.)';
$lang->project->summary                = "%s {$lang->projectCommon}s on this page.";
$lang->project->allSummary             = "%s {$lang->projectCommon}s on this page: %s Waiting, %s Doing, %s On Hold, %s Closed.";
$lang->project->checkedSummary         = "%total% {$lang->projectCommon} selected.";
$lang->project->checkedAllSummary      = "%total% {$lang->projectCommon} selected: %wait% Waiting,  %doing% Doing, %suspended% On Hold, %closed% Closed.";
$lang->project->selectProductTip       = "If no {$lang->productCommon} is selected, the system will automatically create a {$lang->productCommon} with the same name as the {$lang->projectCommon}.";

$lang->project->error = new stdclass();
$lang->project->error->existProductName = "{$lang->productCommon} name already exists.";
$lang->project->error->budgetGe0        = '『Budget』must be greater than or equal to 0.';
$lang->project->error->budgetNumber     = '『Budget』must be numbers.';
$lang->project->error->productNotEmpty  = "Please link {$lang->productCommon}s or create {$lang->productCommon}s.";
$lang->project->error->emptyBranch      = 'Branch can not be empty!';
$lang->project->error->endLessBegin     = 'The planned end date cannot be earlier than the planned start date.';

$lang->project->tip = new stdclass();
$lang->project->tip->closed     = 'The project is already closed and does not need to be closed again.';
$lang->project->tip->notSuspend = 'The project has been closed and cannot be set on hold.';
$lang->project->tip->suspended  = 'The project is already on hold and cannot be set on hold again.';
$lang->project->tip->actived    = 'The project is already active and does not need to be reactivated.';
$lang->project->tip->group      = "It is a Kanban project. Editing permission group is not available.";
$lang->project->tip->whitelist  = "It is a public project with open permissions. No need to edit whitelists.";

$lang->project->tenThousand    = 'Ten Thousand';
$lang->project->hundredMillion = 'Hundred Million';

$lang->project->unitList['CNY'] = 'RMB';
$lang->project->unitList['USD'] = 'USD';
$lang->project->unitList['HKD'] = 'HKD';
$lang->project->unitList['NTD'] = 'NTD';
$lang->project->unitList['EUR'] = 'EUR';
$lang->project->unitList['DEM'] = 'DEM';
$lang->project->unitList['CHF'] = 'CHF';
$lang->project->unitList['FRF'] = 'FRF';
$lang->project->unitList['GBP'] = 'GBP';
$lang->project->unitList['NLG'] = 'NLG';
$lang->project->unitList['CAD'] = 'CAD';
$lang->project->unitList['RUR'] = 'RUB';
$lang->project->unitList['INR'] = 'IDR';
$lang->project->unitList['AUD'] = 'AUD';
$lang->project->unitList['NZD'] = 'NZD';
$lang->project->unitList['THB'] = 'THB';
$lang->project->unitList['SGD'] = 'SGD';

$lang->project->currencySymbol['CNY'] = '¥';
$lang->project->currencySymbol['USD'] = '$';
$lang->project->currencySymbol['HKD'] = 'HK$';
$lang->project->currencySymbol['NTD'] = 'NT$';
$lang->project->currencySymbol['EUR'] = '€';
$lang->project->currencySymbol['DEM'] = 'DEM';
$lang->project->currencySymbol['CHF'] = '₣';
$lang->project->currencySymbol['FRF'] = '₣';
$lang->project->currencySymbol['GBP'] = '£';
$lang->project->currencySymbol['NLG'] = 'ƒ';
$lang->project->currencySymbol['CAD'] = '$';
$lang->project->currencySymbol['RUR'] = '$';
$lang->project->currencySymbol['INR'] = '₹';
$lang->project->currencySymbol['AUD'] = 'A$';
$lang->project->currencySymbol['NZD'] = 'A$';
$lang->project->currencySymbol['THB'] = '฿';
$lang->project->currencySymbol['SGD'] = 'S$';

$lang->project->modelList['']          = '';
if($config->edition == 'ipd') $lang->project->modelList['ipd'] = "IPD";
$lang->project->modelList['scrum']     = "Scrum";
if(helper::hasFeature('waterfall')) $lang->project->modelList['waterfall'] = "CMMI";
$lang->project->modelList['kanban']    = "Kanban";
$lang->project->modelList['agileplus'] = "Agile +";
if(helper::hasFeature('waterfallplus')) $lang->project->modelList['waterfallplus'] = "Waterfall +";

$lang->project->featureBar['browse']['all']    = 'All';
$lang->project->featureBar['browse']['undone'] = 'Uncompleted';
$lang->project->featureBar['browse']['wait']   = 'Waiting';
$lang->project->featureBar['browse']['doing']  = 'Doing';
$lang->project->featureBar['browse']['more']   = 'More';

$lang->project->featureBar['index']['all']       = 'All';
$lang->project->featureBar['index']['undone']    = 'Uncompleted';
$lang->project->featureBar['index']['wait']      = 'Waiting';
$lang->project->featureBar['index']['doing']     = 'Doing';
$lang->project->featureBar['index']['suspended'] = 'On Hold';
$lang->project->featureBar['index']['closed']    = 'Closed';

$lang->project->featureBar['execution']['all']       = 'All';
$lang->project->featureBar['execution']['undone']    = 'Uncompleted';
$lang->project->featureBar['execution']['wait']      = 'Waiting';
$lang->project->featureBar['execution']['doing']     = 'Doing';
$lang->project->featureBar['execution']['suspended'] = 'On Hold';
$lang->project->featureBar['execution']['delayed']   = 'Delayed';
$lang->project->featureBar['execution']['closed']    = 'Closed';

$lang->project->featureBar['bug']['all']        = 'All';
$lang->project->featureBar['bug']['unresolved'] = 'Unresolved';

$app->loadLang('testcase');
$lang->project->featureBar['testcase'] = $lang->testcase->featureBar['browse'];

$lang->project->featureBar['build']['all'] = 'All Builds';

$lang->project->featureBar['group']['all'] = 'All Groups';

$lang->project->aclList['open']    = "Public (Accessible to anyone with \"{$lang->projectCommon}\" view permissions.)";
$lang->project->aclList['private'] = "Private (For the {$lang->projectCommon} manager, team members and stakeholders only.)";

$lang->project->taskDateLimitList['limit'] = "Restrict Subtask Dates (Must stay within parent task period)";
$lang->project->taskDateLimitList['auto']  = "Auto-extend Parent Task (Adjusts based on subtask dates)";

$lang->project->multipleList['1'] = 'Yes';
$lang->project->multipleList['0'] = 'No';

$lang->project->acls['private'] = 'Private';
$lang->project->acls['open']    = 'Public';

$lang->project->shortAclList['open']    = 'Public';
$lang->project->shortAclList['private'] = 'Private';
$lang->project->shortAclList['program'] = 'Public in the Program';

$lang->project->subAclList['open']    = "Public (Accessible to anyone with \"{$lang->projectCommon}\" view permissions.)";
$lang->project->subAclList['private'] = "Private (Accessible only to {$lang->projectCommon} manager, team members and stakeholders.)";
$lang->project->subAclList['program'] = "Public within Program (Accessible to all upper-level program managers and stakeholders, the {$lang->projectCommon} manager, team members and stakeholders.)";

$lang->project->kanbanAclList['open']    = "Public (Accessible to anyone with \"{$lang->projectCommon}\" view permissions.)";
$lang->project->kanbanAclList['private'] = "Private (For the {$lang->projectCommon} manager, team members only)";

$lang->project->kanbanSubAclList['open']    = "Public (Accessible to anyone with \"{$lang->projectCommon}\" view permissions.)";
$lang->project->kanbanSubAclList['private'] = "Private (Accessible only to {$lang->projectCommon} manager, team members)";
$lang->project->kanbanSubAclList['program'] = "Public within Program (Accessible to all upper-level program managers and stakeholders, the {$lang->projectCommon} manager, team members.)";

if($config->systemMode == 'light')
{
    unset($lang->project->subAclList['program']);
    unset($lang->project->kanbanSubAclList['program']);
}

$lang->project->authList['extend'] = "Inherit (Union of System and {$lang->projectCommon} permissions)";
$lang->project->authList['reset']  = "Override ({$lang->projectCommon} permissions only)";

$lang->project->sortAuthList['extend'] = 'Inherit';
$lang->project->sortAuthList['reset']  = 'Reset';

$lang->project->statusList['']          = '';
$lang->project->statusList['wait']      = 'Waiting';
$lang->project->statusList['doing']     = 'Doing';
$lang->project->statusList['suspended'] = 'On Hold';
$lang->project->statusList['closed']    = 'Closed';
$lang->project->statusList['delay']     = 'Delayed';

$lang->project->endList[31]  = 'One month';
$lang->project->endList[93]  = '3 months';
$lang->project->endList[186] = 'Half year';
$lang->project->endList[365] = 'One year';
$lang->project->endList[999] = 'Long-term';

$lang->project->ipdTitle           = "Integrated Product Development";
$lang->project->scrumTitle         = 'Agile Development Management';
$lang->project->waterfallTitle     = "Waterfall {$lang->projectCommon} Management";
$lang->project->kanbanTitle        = "Kanban {$lang->projectCommon} Management";
$lang->project->agileplusTitle     = "Scrum + Kanban {$lang->projectCommon} Management";
$lang->project->waterfallplusTitle = "Waterfall + Scrum + Kanban {$lang->projectCommon} Management";
$lang->project->moreModelTitle     = 'More models will coming soon.';

$lang->project->empty                  = "No {$lang->projectCommon}s available yet.";
$lang->project->nextStep               = 'Next';
$lang->project->hoursUnit              = '%s hours';
$lang->project->workHourUnit           = 'H';
$lang->project->membersUnit            = '%s men';
$lang->project->lastIteration          = "Recent {$lang->executionCommon}";
$lang->project->lastKanban             = 'Recent Kanban';
$lang->project->ongoingStage           = 'Ongoing Phases';
$lang->project->ipd                    = 'IPD';
$lang->project->scrum                  = 'Scrum';
$lang->project->waterfall              = 'Waterfall';
$lang->project->agileplus              = 'Agile +';
$lang->project->waterfallplus          = 'Waterfall +';
$lang->project->cannotCreateChild      = 'It is not empty, so you cannot add a child. You can add a parent for it, and then create a child.';
$lang->project->emptyPM                = 'No data available.';
$lang->project->cannotChangeToCat      = "It is not empty, so you cannot change it to a parent.";
$lang->project->cannotCancelCat        = "It has child {$lang->projectCommon}s, so you cannot unmark the parent.";
$lang->project->parentBeginEnd         = "Parent begin&end date: %s ~ %s";
$lang->project->childLongTime          = "If a child as long-term {$lang->projectCommon}s, the parent should be long-term too.";
$lang->project->readjustTime           = "Change the {$lang->projectCommon} start and end dates.";
$lang->project->notAllowRemoveProducts = "The stories in this {$lang->productCommon} are linked to the {$lang->projectCommon}, or an {$lang->execution->common} under the {$lang->projectCommon}is linked to this {$lang->productCommon}. Please remove the associations and try again.";
$lang->project->ge                     = "『%s』should not be earlier than the actual start date『%s』.";

$lang->project->programTitle['0']    = 'Hidden';
$lang->project->programTitle['base'] = "Show top-level {$lang->projectCommon} only";
$lang->project->programTitle['end']  = "Show lowest-level {$lang->projectCommon} only";

$lang->project->accessDenied            = "Access denied to this {$lang->projectCommon}.";
$lang->project->chooseProgramType       = 'Select management type';
$lang->project->cannotCreateChild       = "It already contains actual data, so you cannot add a child. You can add a parent for it, and then create a child.";
$lang->project->hasChildren             = "This {$lang->projectCommon} has a child {$lang->projectCommon}, so it cannot be deleted.";
$lang->project->confirmDelete           = "Are you sure you want to delete {$lang->projectCommon}“%s”?";
$lang->project->confirmDisableStoryType = "After cancellation, all corresponding conceptual stories that linked to the project and its executions will be removed. This action is irreversible.";
$lang->project->cannotChangeToCat       = "It already contains actual data, so you cannot convert it into a parent.";
$lang->project->cannotCancelCat         = "It has child {$lang->projectCommon}s, so you cannot unmark the parent.";
$lang->project->parentBeginEnd          = "Parent {$lang->projectCommon} duration: %s ~ %s";
$lang->project->parentBudget            = "The budget of the parent program: ";
$lang->project->confirmCreateStage      = "Data already exists under this phase. Are you sure you want to split it? If you proceed with the split, to maintain data consistency, existing data will be recorded in the first {$lang->execution->common} created from the split.";

$lang->project->beginLessThanParent     = "The start date of the {$lang->projectCommon} is earlier than the start date of the parent program: %s.";
$lang->project->endGreatThanParent      = "The end date of the {$lang->projectCommon} is later than the end date of the parent program: %s. ";
$lang->project->dateExceedParent        = "The start and end date of the {$lang->projectCommon} exceeds the data range of the parent program:";
$lang->project->beginGreatEqualChild    = "The start date of the {$lang->projectCommon} should be on or after the start date of program: %s.";
$lang->project->endLessThanChild        = "The end date of the {$lang->projectCommon} should be on or before the end date of program: %s.";
$lang->project->beginLessEqualExecution = "The start date of {$lang->projectCommon} should be on or before the start date of the earliest execution: %s.";
$lang->project->endGreatEqualExecution  = "The end date of the {$lang->projectCommon} should be on or after the end date of the execution: %s.";

$lang->project->childLongTime        = "Since there are long-term {$lang->projectCommon}s under this parent, the parent {$lang->projectCommon} should also be marked as long-term.";
$lang->project->confirmUnlinkMember  = "Are you sure you want to remove the user from {$lang->projectCommon}?";
$lang->project->stageByTips          = "Create a single phase set for the {$lang->projectCommon} where each phase is linked to all {$lang->productCommon}s; or create multiple phase sets by {$lang->productCommon}, with each set linked to one {$lang->productCommon} only.";
$lang->project->confirmCloseProject  = "There are unclosed {$lang->executionCommon}s under this {$lang->projectCommon}: %s. Are you sure you want to close the {$lang->projectCommon}?";

$lang->project->action = new stdclass();
$lang->project->action->managed = '$date, managed by <strong>$actor</strong>. $extra' . "\n";

$lang->project->multiple = "Enable {$lang->executionCommon}";

$lang->project->copyProject = new stdClass();
$lang->project->copyProject->nameTips           = "『{$lang->projectCommon} Name』Cannot be repeated.";
$lang->project->copyProject->codeTips           = "『{$lang->projectCommon} Code』Cannot be repeated.";
$lang->project->copyProject->endTips            = '『Planned End』Cannot be empty.';
$lang->project->copyProject->daysTips           = '『Work Days』Should be a numeric value.';

$lang->project->linkBranchStoryByPlanTips = "When linking stories by plan, only the active stories associated with the {$lang->projectCommon} %s will be imported.";
$lang->project->linkNormalStoryByPlanTips = "When linking stories by plan, only the active stories will be imported.";
$lang->project->cannotManageProducts      = "This project is a {$lang->projectCommon}-based {$lang->projectCommon} and cannot be associated with {$lang->productCommon}s.";

$lang->project->featureBar['dynamic']['all']       = 'All';
$lang->project->featureBar['dynamic']['today']     = 'Today';
$lang->project->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->project->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->project->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->project->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->project->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->project->moreSelects = array();
$lang->project->moreSelects['suspended'] = 'On Hold';
$lang->project->moreSelects['delayed']   = 'Delayed';
$lang->project->moreSelects['closed']    = 'Closed';

$lang->project->executionList['scrum']         = $lang->projectCommon . ' Sprint';
$lang->project->executionList['waterfall']     = $lang->projectCommon . ' Phase';
$lang->project->executionList['kanban']        = $lang->projectCommon . ' Kanban';
$lang->project->executionList['agileplus']     = $lang->projectCommon . ' Sprint';
$lang->project->executionList['waterfallplus'] = $lang->projectCommon . ' Phase';

$lang->project->featureBar['team']['all'] = 'Members';

$lang->project->featureBar['managemembers']['all'] = 'Manage Team';

$lang->project->coverExecutionPrivList = array();
$lang->project->coverExecutionPrivList[1] = 'Open (After enabling, you can control permissions for executing functions (such as tasks, burn-down charts, tests, etc.) in the "Project Custom Permissions" section. This action is irreversible.)';
$lang->project->coverExecutionPrivList[0] = 'Close';

$lang->project->api = new stdclass();
$lang->project->api->error = new stdclass();
$lang->project->api->error->productNotFound = 'The product is not exist.';

$lang->project->disabledHint = new stdclass();
$lang->project->disabledHint->linkedStory             = "{$lang->projectCommon} has {$lang->SRCommon} linked from {$lang->productCommon} and cannot be unlinked. Please unlink {$lang->SRCommon} first before proceeding.";
$lang->project->disabledHint->createdStage            = "{$lang->productCommon} has existing phases. To unlink it from {$lang->projectCommon}, please delete the existing phases first.";
$lang->project->disabledHint->linkedStoryAndStage     = "{$lang->productCommon} has existing phases and linked {$lang->SRCommon}. To unlink it from {$lang->projectCommon}, please remove the {$lang->SRCommon} associations first and then delete the existing phased.";
$lang->project->disabledHint->linkedStoryAndExecution = "{$lang->SRCommon} under {$lang->productCommon} is currently linked to {$lang->projectCommon} and {$lang->execution->common}. Please remove the {$lang->SRCommon} associations from {$lang->projectCommon} and {$lang->execution->common} first before proceeding.";
