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
$lang->project->view                = "{$lang->projectCommon} View";
$lang->project->batchEdit           = "Batch Edit {$lang->projectCommon}s";
$lang->project->browse              = $lang->projectCommon;
$lang->project->all                 = 'All';
$lang->project->involved            = "My Involved";
$lang->project->start               = 'Start';
$lang->project->finish              = 'Finish';
$lang->project->suspend             = 'Suspend';
$lang->project->delete              = 'Delete';
$lang->project->close               = 'Close';
$lang->project->activate            = 'Activate';
$lang->project->group               = 'Privilege List';
$lang->project->createGroup         = 'Create Group';
$lang->project->editGroup           = 'Edit Group';
$lang->project->copyGroup           = 'Copy Group';
$lang->project->manageView          = 'Manage View';
$lang->project->managePriv          = 'Manage Privilege';
$lang->project->manageMembers       = 'Manage Team';
$lang->project->export              = 'Export';
$lang->project->addProduct          = "Add {$lang->productCommon}";
$lang->project->manageGroupMember   = 'Manage Group';
$lang->project->moduleSetting       = 'List Settings';
$lang->project->moduleOpen          = 'Program Name';
$lang->project->moduleOpenAction    = 'Program Name Settings';
$lang->project->dynamic             = 'Dynamic';
$lang->project->execution           = 'Execution';
$lang->project->bug                 = 'Bug List';
$lang->project->testcase            = 'Case List';
$lang->project->testtask            = 'Test Task';
$lang->project->build               = 'Build';
$lang->project->updateOrder         = 'Order';
$lang->project->sort                = 'Order';
$lang->project->whitelist           = "{$lang->projectCommon} Whitelist";
$lang->project->addWhitelist        = "{$lang->projectCommon} Add Whitelist";
$lang->project->unbindWhitelist     = "{$lang->projectCommon} Remove Whitelist";
$lang->project->manageProducts      = "Manage {$lang->productCommon}s";
$lang->project->manageOtherProducts = "Manage Other {$lang->productCommon}s";
$lang->project->manageProductPlan   = "Manage {$lang->productCommon}s And Plans";
$lang->project->copyTitle           = "Please select an {$lang->projectCommon} to copy";
$lang->project->errorSameProducts   = "{$lang->projectCommon} cannot be associated with multiple identical {$lang->productCommon}s.";
$lang->project->errorSameBranches   = "{$lang->projectCommon} cannot be associated with multiple identical branches.";
$lang->project->errorSamePlans      = "{$lang->projectCommon} cannot be associated with multiple identical plans.";
$lang->project->errorNoProducts     = "At least one {$lang->productCommon} is associated";
$lang->project->copyNoProject       = 'There are no items available to copy.';
$lang->project->searchByName        = "Enter the {$lang->projectCommon} name to search";
$lang->project->emptyProgram        = "Independent Programs";
$lang->project->deleted             = 'Deleted';
$lang->project->linkedProducts      = "Linked {$lang->productCommon}s";
$lang->project->unlinkedProducts    = "Unlinked {$lang->productCommon}s";
$lang->project->testreport          = 'Test Report';
$lang->project->selectProgram       = 'Program filtering';
$lang->project->teamMember          = 'Team Member';
$lang->project->unlinkMember        = 'Remove Member';
$lang->project->unlinkMemberAction  = 'Remove Team Member';
$lang->project->copyTeamTitle       = "Select a {$lang->projectCommon} team to copy";
$lang->project->daysGreaterProject  = "Days cannot be greater than days of {$lang->projectCommon}『%s』";
$lang->project->errorHours          = 'Hours/Day cannot be greater than『24』';
$lang->project->workdaysExceed      = 'No more than『%s』working days';
$lang->project->teamMembersCount    = ', there are %s team members.';
$lang->project->allProjects         = "All {$lang->projectCommon}s";
$lang->project->ignore              = 'Ignore';
$lang->project->disableExecution    = "{$lang->projectCommon} of disable {$lang->executionCommon}";
$lang->project->selectProduct       = "Select {$lang->productCommon}";
$lang->project->manageRepo          = 'Manage Repo';
$lang->project->linkedRepo          = 'Link Repo';
$lang->project->unlinkedRepo        = 'Unlink Repo';
$lang->project->executionCount      = 'Total Executions';
$lang->project->storyCount          = 'Story Points';
$lang->project->invested            = 'Invested';
$lang->project->member              = 'Member';
$lang->project->manage              = 'Manage';

$lang->project->manDay          = 'Man Day';
$lang->project->day             = 'Day';
$lang->project->newProduct      = 'New Product';
$lang->project->associatePlan   = 'Associate Plan';
$lang->project->tenThousandYuan = '10k';
$lang->project->planDate        = 'Schedule Date';
$lang->project->delayInfo       = 'Delayed for %s days';

/* Fields. */
$lang->project->common             = $lang->projectCommon;
$lang->project->id                 = 'ID';
$lang->project->project            = $lang->projectCommon;
$lang->project->stage              = 'Stage';
$lang->project->model              = 'Model';
$lang->project->PM                 = 'Manager';
$lang->project->PO                 = "{$lang->projectCommon} Owner";
$lang->project->QD                 = 'Test Leader';
$lang->project->RD                 = 'Releaser';
$lang->project->name               = 'Name';
$lang->project->category           = 'Category';
$lang->project->desc               = 'Description';
$lang->project->code               = 'Code';
$lang->project->hasProduct         = "Has {$lang->productCommon}";
$lang->project->copy               = 'Copy';
$lang->project->begin              = 'Planned Begin';
$lang->project->end                = 'Planned End';
$lang->project->status             = 'Status';
$lang->project->subStatus          = 'Sub Status';
$lang->project->type               = 'Type';
$lang->project->lifetime           = "{$lang->projectCommon} Cycle";
$lang->project->attribute          = 'Stage Type';
$lang->project->percent            = 'Workload %';
$lang->project->milestone          = 'Milestone';
$lang->project->output             = 'Output';
$lang->project->path               = 'Path';
$lang->project->grade              = 'Grade';
$lang->project->version            = 'Version';
$lang->project->program            = 'Program';
$lang->project->parentVersion      = 'Parent Version';
$lang->project->planDuration       = 'Plan Duration';
$lang->project->realDuration       = 'Real Duration';
$lang->project->openedVersion      = 'Opened Version';
$lang->project->pri                = 'Priority';
$lang->project->openedBy           = 'OpenedBy';
$lang->project->openedDate         = 'OpenedDate';
$lang->project->lastEditedBy       = 'Last EditedBy';
$lang->project->lastEditedDate     = 'Last EditedDate';
$lang->project->closedBy           = 'ClosedBy';
$lang->project->closedDate         = 'ClosedDate';
$lang->project->canceledBy         = 'CanceledBy';
$lang->project->canceledDate       = 'CanceledDate';
$lang->project->team               = 'Team';
$lang->project->teamAction         = 'Team List';
$lang->project->order              = 'Rank';
$lang->project->budget             = 'Budget';
$lang->project->budgetUnit         = "(Unit: {$lang->project->tenThousandYuan})";
$lang->project->suspendedDate      = 'SuspendedDate';
$lang->project->vision             = 'Vision';
$lang->project->displayCards       = 'Max cards per column';
$lang->project->fluidBoard         = 'Column Width';
$lang->project->template           = 'Template';
$lang->project->estimate           = 'Estimates';
$lang->project->consume            = 'Cost';
$lang->project->surplus            = 'Left';
$lang->project->progress           = 'Progress';
$lang->project->weekProgress       = 'This Week Progress';
$lang->project->dateRange          = 'Plan Duration';
$lang->project->to                 = ' to ';
$lang->project->realBeganAB        = 'Actual Begin';
$lang->project->realEndAB          = 'Actual End';
$lang->project->realBegan          = 'Actual Begin';
$lang->project->realEnd            = 'Actual End';
$lang->project->stageBy            = 'Stage Type';
$lang->project->bygrid             = 'Kanban';
$lang->project->bylist             = 'List';
$lang->project->bycard             = 'Card';
$lang->project->mine               = 'My';
$lang->project->myProject          = 'Mine';
$lang->project->other              = 'Others';
$lang->project->acl                = 'ACL';
$lang->project->setPlanduration    = 'Set Duration';
$lang->project->auth               = 'Privileges';
$lang->project->durationEstimation = 'Estimated Workload';
$lang->project->leftStories        = 'Left Stories';
$lang->project->leftTasks          = 'Left Tasks';
$lang->project->leftBugs           = 'Left Bugs';
$lang->project->leftHours          = 'Left Hours';
$lang->project->children           = "Child {$lang->projectCommon}";
$lang->project->parent             = 'Parent Program';
$lang->project->allStories         = 'All Stories';
$lang->project->allProgress        = 'All Progress';
$lang->project->doneStories        = 'Finished Stories';
$lang->project->doneProjects       = 'Finished';
$lang->project->allInput           = 'Total Input';
$lang->project->weekly             = 'Program Weekly';
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
$lang->project->teamCount          = 'Team';
$lang->project->teamSumCount       = '%s people in total';
$lang->project->longTime           = 'Long-Term';
$lang->project->future             = 'TBD';
$lang->project->moreProject        = "More {$lang->projectCommon}";
$lang->project->days               = 'Days';
$lang->project->daysUnit           = '(Unit: Days)';
$lang->project->mailto             = 'Mailto';
$lang->project->etc                = " , etc";
$lang->project->product            = $lang->productCommon;
$lang->project->branch             = 'Platform/Branch';
$lang->project->plan               = 'Plan';
$lang->project->createKanban       = 'Create Kanban';
$lang->project->kanban             = 'Kanban';
$lang->project->moreActions        = 'More Actions';

/* Project Category. */
$lang->project->projectTypeList = array();
$lang->project->projectTypeList[1] = "{$lang->productCommon}";
$lang->project->projectTypeList[0] = "Non-{$lang->productCommon}";

/* Project Kanban. */
$lang->project->typeList = array();
$lang->project->typeList['my']    = "{$lang->projectCommon}s Ownedbyme";
$lang->project->typeList['other'] = "Other {$lang->projectCommon}s";

$lang->project->stageByList['product'] = "Create by {$lang->projectCommon}";
$lang->project->stageByList['product'] = "Create by {$lang->productCommon}";

$lang->project->stageBySwitchList['0'] = 'Close';
$lang->project->stageBySwitchList['1'] = "Open";

$lang->project->waitProjects    = "Waiting {$lang->projectCommon}s";
$lang->project->doingProjects   = "Ongoing {$lang->projectCommon}s";
$lang->project->doingExecutions = 'Ongoing Executions';
$lang->project->closedProjects  = "Closed {$lang->projectCommon}s(The recent two projects)";
$lang->project->closedProject   = "Closed {$lang->projectCommon}s";
$lang->project->noProgram       = "Independent {$lang->projectCommon}s";

$lang->project->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$lang->project->changeProgram          = "%s > Change {$lang->projectCommon}";
$lang->project->changeProgramTip       = "Once the program is edited, the {$lang->productCommon} that is linked to this program will be changed. Do you want to edit it?";
$lang->project->linkedProjectsTip      = "Linked {$lang->projectCommon}s are as follows";
$lang->project->multiLinkedProductsTip = "The following {$lang->productCommon}s linked to this {$lang->projectCommon} are also linked to other {$lang->projectCommon}s, please unlink before proceeding.";
$lang->project->noticeDivsion          = "The current {$lang->projectCommon} is a single stage, click [Open] to change to multiple stages, each stage is only associated with one {$lang->productCommon}.";
$lang->project->linkStoryByPlanTips    = "This action will associate all {$lang->SRCommon} under the selected plan to this {$lang->projectCommon}";
$lang->project->createExecution        = "There is no {$lang->executionCommon} under this project, please create {$lang->executionCommon} first";
$lang->project->unlinkExecutionMember  = "The user participated in %s executions such as %s%s. Do you want to remove the user from those executions as well? (The data related to this user will not be deleted.)";
$lang->project->unlinkExecutionMembers = "The team members you are removing are also in the execution team of this {$lang->projectCommon}. Do you want to remove them from the execution team too?";
$lang->project->productTip             = "After clicking New {$lang->productCommon}, the {$lang->projectCommon} will not be linked to the selected {$lang->productCommon}.";
$lang->project->noDevStage             = "There is no R&D stage under this {$lang->projectCommon}, or you do not have access permissions. The creation of builds is not supported at the moment.";
$lang->project->budgetOverrun          = "The {$lang->projectCommon}'s budget exceeds the remaining budget of the parent program: <strong id='currency'></strong><strong id='parentBudget'></strong><strong id='budgetUnit'></strong>. ";
$lang->project->disabledInputTip       = 'Please cancel %s first';
$lang->project->linkRepoFailed         = "Failed to link {$lang->projectCommon}s and code repositories.";
$lang->project->unLinkProductTip       = 'Are you sure you want to unlink %s? (Does not affect linked requirements)';
$lang->project->summary                = "Total {$lang->projectCommon}s: %s.";
$lang->project->allSummary             = "Total {$lang->projectCommon}s: %s, Wait: %s, Doing: %s, Suspended: %s, Closed: %s.";
$lang->project->checkedSummary         = 'Seleted: %total%.';
$lang->project->checkedAllSummary      = 'Seleted: %total%, Wait: %wait%, Doing: %doing%, Suspended: %suspended%, Closed: %closed%.';

$lang->project->tip = new stdclass();
$lang->project->tip->closed     = 'The project has been closed. Re-close is not available.';
$lang->project->tip->notSuspend = 'The project has been closed. Suspend is not available.';
$lang->project->tip->suspended  = 'The project has been suspended. Re-suspend is not available.';
$lang->project->tip->actived    = 'The project has been activated. Re-activated is not available.';
$lang->project->tip->group      = "It's a Kanban project. Editing privilege group is not available.";
$lang->project->tip->whitelist  = "It's a public project with open permissions. No need to edit whitelists.";

$lang->project->error = new stdclass();
$lang->project->error->existProductName = "{$lang->productCommon} name already exists.";
$lang->project->error->budgetGe0        = '『Budget』must be greater than or equal to 0.';
$lang->project->error->budgetNumber     = '『Budget』must be numbers.';
$lang->project->error->productNotEmpty  = "Please link {$lang->productCommon}s or create {$lang->productCommon}s.";
$lang->project->error->emptyBranch      = 'Branch can not be empty!';
$lang->project->error->endLessBegin     = 'The end date must be greater than the start date.';

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
$lang->project->unitList['GBP'] = 'GPR';
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
$lang->project->currencySymbol['RUR'] = '₽';
$lang->project->currencySymbol['INR'] = '₹';
$lang->project->currencySymbol['AUD'] = 'A$';
$lang->project->currencySymbol['NZD'] = 'NZ$';
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
$lang->project->featureBar['browse']['undone'] = 'Unfinished';
$lang->project->featureBar['browse']['wait']   = 'Waiting';
$lang->project->featureBar['browse']['doing']  = 'Doing';
$lang->project->featureBar['browse']['more']   = 'More';

$lang->project->featureBar['index']['all']       = 'All';
$lang->project->featureBar['index']['undone']    = 'Unfinished';
$lang->project->featureBar['index']['wait']      = 'Waiting';
$lang->project->featureBar['index']['doing']     = 'Doing';
$lang->project->featureBar['index']['suspended'] = 'Suspended';
$lang->project->featureBar['index']['closed']    = 'Closed';

$lang->project->featureBar['execution']['all']       = 'All';
$lang->project->featureBar['execution']['undone']    = 'Unfinished';
$lang->project->featureBar['execution']['wait']      = 'Waiting';
$lang->project->featureBar['execution']['doing']     = 'Doing';
$lang->project->featureBar['execution']['suspended'] = 'Suspended';
$lang->project->featureBar['execution']['closed']    = 'Closed';

$lang->project->featureBar['bug']['all']        = 'All';
$lang->project->featureBar['bug']['unresolved'] = 'Unresolved';

$app->loadLang('testcase');
$lang->project->featureBar['testcase'] = $lang->testcase->featureBar['browse'];

$lang->project->featureBar['build']['all'] = 'Build List';

$lang->project->featureBar['group']['all'] = 'All Groups';

$lang->project->aclList['private'] = "Private (For the {$lang->projectCommon} leader, team members and stakeholders only)";
$lang->project->aclList['open']    = "Open (accessible with {$lang->projectCommon} view permissions)";

$lang->project->multipleList['1'] = 'Yes';
$lang->project->multipleList['0'] = 'No';

$lang->project->acls['private'] = 'Private';
$lang->project->acls['open']    = 'Open';

$lang->project->subAclList['private'] = "Private (Only the {$lang->projectCommon} leader, team members and stakeholders can access)";
$lang->project->subAclList['open']    = "Open (accessible with {$lang->projectCommon} view permissions)";
$lang->project->subAclList['program'] = "Open in the program (all upper-level program team leaders and stakeholders, the {$lang->projectCommon} leader, team members and stakeholders can access)";

$lang->project->kanbanAclList['private'] = "Private (For the {$lang->projectCommon} leader, team members only)";
$lang->project->kanbanAclList['open']    = "Open (accessible with {$lang->projectCommon} view permissions)";

$lang->project->kanbanSubAclList['private'] = "Private (Only the {$lang->projectCommon} leader, team members can access)";
$lang->project->kanbanSubAclList['open']    = "Open (accessible with {$lang->projectCommon} view permissions)";
$lang->project->kanbanSubAclList['program'] = "Open in the program (all upper-level program team leaders and stakeholders, the {$lang->projectCommon} leader, team members can access)";

if($config->systemMode == 'light')
{
    unset($lang->project->subAclList['program']);
    unset($lang->project->kanbanSubAclList['program']);
}

$lang->project->authList['extend'] = "Inherit (system privilege and {$lang->projectCommon} privilege)";
$lang->project->authList['reset']  = "Reset ({$lang->projectCommon} privilege only)";

$lang->project->statusList['']          = '';
$lang->project->statusList['wait']      = 'Waiting';
$lang->project->statusList['doing']     = 'Doing';
$lang->project->statusList['suspended'] = 'Suspended';
$lang->project->statusList['closed']    = 'Closed';
$lang->project->statusList['delay']     = 'Delayed';

$lang->project->endList[31]  = 'One month';
$lang->project->endList[93]  = 'Trimester';
$lang->project->endList[186] = 'Half year';
$lang->project->endList[365] = 'One year';
$lang->project->endList[999] = 'Longtime';

$lang->project->ipdTitle           = "Integrated Product Development";
$lang->project->scrumTitle         = 'Agile Development Management';
$lang->project->waterfallTitle     = "Waterfall {$lang->projectCommon} Management";
$lang->project->kanbanTitle        = "Kanban {$lang->projectCommon} Management";
$lang->project->agileplusTitle     = "Scrum + Kanban {$lang->projectCommon} Management";
$lang->project->waterfallplusTitle = "Waterfall + Scrum + Kanban {$lang->projectCommon} Management";
$lang->project->moreModelTitle     = 'Please look forward to more models...';

$lang->project->empty                  = "No {$lang->projectCommon}.";
$lang->project->nextStep               = 'Next step';
$lang->project->hoursUnit              = '%s hours';
$lang->project->workHourUnit           = 'H';
$lang->project->membersUnit            = '%s men';
$lang->project->lastIteration          = "Recent {$lang->executionCommon}";
$lang->project->lastKanban             = 'Recent Kanban';
$lang->project->ongoingStage           = 'Ongoing stage';
$lang->project->ipd                    = 'IPD';
$lang->project->scrum                  = 'Scrum';
$lang->project->waterfall              = 'Waterfall';
$lang->project->agileplus              = 'Agile +';
$lang->project->waterfallplus          = 'Waterfall +';
$lang->project->cannotCreateChild      = 'It is not empty, so you cannot add a child. You can add a parent for it, and then create a child.';
$lang->project->emptyPM                = 'No manager';
$lang->project->cannotChangeToCat      = "It is not empty, so you cannot change it to a parent.";
$lang->project->cannotCancelCat        = "It has child {$lang->projectCommon}s, so you cannot unmark the parent.";
$lang->project->parentBeginEnd         = "Parent begin&end date: %s ~ %s";
$lang->project->childLongTime          = "If a child as long-term {$lang->projectCommon}s, the parent should be long-term too.";
$lang->project->readjustTime           = "Change the {$lang->projectCommon} begin&end date.";
$lang->project->notAllowRemoveProducts = "Stories of this {$lang->productCommon} are linked to {$lang->projectCommon}s or {$lang->execution->common} of this {$lang->projectCommon} is linked to this {$lang->productCommon}. Please unlink it and try again.";
$lang->project->ge                     = "『%s』should be >= actual begin『%s』.";

$lang->project->programTitle['0']    = 'Hidden';
$lang->project->programTitle['base'] = "Base-level {$lang->projectCommon} only";
$lang->project->programTitle['end']  = "End-level {$lang->projectCommon} only";

$lang->project->accessDenied         = "Access denied to this {$lang->projectCommon}";
$lang->project->chooseProgramType    = 'Select management type';
$lang->project->cannotCreateChild    = "The {$lang->projectCommon} has contents, so you cannot add a child {$lang->projectCommon}. You can create a parent project for this one and then add a child {$lang->projectCommon} for the parent {$lang->projectCommon}.";
$lang->project->hasChildren          = "This {$lang->projectCommon} has a child {$lang->projectCommon}, so it cannot be deleted.";
$lang->project->confirmDelete        = 'Do you want to delete \"%s\"?';
$lang->project->cannotChangeToCat    = "The {$lang->projectCommon} has contents, so you cannot it to a parent {$lang->projectCommon}.";
$lang->project->cannotCancelCat      = "There are child {$lang->projectCommon}s of this {$lang->projectCommon}. You cannot cancel the parent {$lang->projectCommon} mark.";
$lang->project->parentBeginEnd       = "The begin and end date of the parent {$lang->projectCommon}: %s ~ %s";
$lang->project->parentBudget         = "The budget of the parent program: ";

$lang->project->beginLessThanParent     = "The start date of the {$lang->projectCommon} is < the start date of the parent program: <strong class='parentBegin'></strong>. ";
$lang->project->endGreatThanParent      = "The finish date of the {$lang->projectCommon} is > the finish date of the parent program: <strong class='parentEnd'></strong>. ";
$lang->project->dateExceedParent        = "The start and finish date of the {$lang->projectCommon} was > the start and finish date of the parent program:";
$lang->project->beginGreatEqualChild    = "The start date of the {$lang->projectCommon} should be ≥ the start date of program: %s.";
$lang->project->endLessThanChild        = "The finish date of the {$lang->projectCommon} should be ≤ the finish date of program: %s.";
$lang->project->beginLessEqualExecution = "The start date of {$lang->projectCommon} should be ≤ the minimum start date of the execution: %s.";
$lang->project->endGreatEqualExecution  = "The finish date of the {$lang->projectCommon} should be ≥ the maximum finish date of the execution: %s.";

$lang->project->childLongTime        = "There are long-term {$lang->projectCommon}s in the child {$lang->projectCommon}, and the parent {$lang->projectCommon} should also be a long-term {$lang->projectCommon}.";
$lang->project->confirmUnlinkMember  = "Do you want to remove this user from {$lang->projectCommon}?";
$lang->project->stageByTips          = "Created by {$lang->projectCommon} as a single set of stages, and the stage is associated with all {$lang->productCommon}s; created by {$lang->productCommon} as multiple sets of stages, each set of stages is associated with a {$lang->productCommon}";

$lang->project->action = new stdclass();
$lang->project->action->managed = '$date, managed by <strong>$actor</strong>. $extra' . "\n";

$lang->project->multiple = "Multi {$lang->executionCommon}";

$lang->project->copyProject = new stdClass();
$lang->project->copyProject->nameTips           = "『{$lang->projectCommon} Name』Cannot be repeated.";
$lang->project->copyProject->codeTips           = "『{$lang->projectCommon} Code』Cannot be repeated.";
$lang->project->copyProject->endTips            = '『Schedule End』Cannot be empty.';
$lang->project->copyProject->daysTips           = '『Available working days』Should be numerical.';

$lang->project->linkBranchStoryByPlanTips = "When the {$lang->projectCommon} is scheduled to associate requirements, only the activated requirements associated with the %s of the {$lang->projectCommon} are imported.";
$lang->project->linkNormalStoryByPlanTips = "When the {$lang->projectCommon} plans to associate requirements, only the requirements that are active are imported.";
$lang->project->cannotManageProducts      = "This project is a {$lang->projectCommon}-type {$lang->projectCommon} and cannot be associated with {$lang->productCommon}s.";

$lang->project->featureBar['dynamic']['all']       = 'All';
$lang->project->featureBar['dynamic']['today']     = 'Today';
$lang->project->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->project->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->project->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->project->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->project->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->project->moreSelects = array();
$lang->project->moreSelects['suspended'] = 'Suspended';
$lang->project->moreSelects['closed']    = 'Closed';

$lang->project->executionList['scrum']         = $lang->projectCommon . ' Sprint';
$lang->project->executionList['waterfall']     = $lang->projectCommon . ' Stage';
$lang->project->executionList['kanban']        = $lang->projectCommon . ' Kanban';
$lang->project->executionList['agileplus']     = $lang->projectCommon . ' Sprint';
$lang->project->executionList['waterfallplus'] = $lang->projectCommon . ' Stage';

$lang->project->featureBar['team']['all'] = 'Members';

$lang->project->featureBar['managemembers']['all'] = 'Manage Team';
