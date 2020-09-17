<?php
/* Actions. */
$lang->program->index                =  'Program';
$lang->program->PRJCreate            =  'Create Program';
$lang->program->PRJCreateGuide       =  'Select Template';
$lang->program->PRJEdit              =  'Edit';
$lang->program->PRJBrowse            =  'Programs';
$lang->program->PRJAll               =  'All';
$lang->program->PRJStart             =  'Start';
$lang->program->PRJFinish            =  'Finish';
$lang->program->PRJSuspend           =  'Suspend';
$lang->program->PRJDelete            =  'Delete';
$lang->program->PRJClose             =  'Close';
$lang->program->PRJActivate          =  'Activate';
$lang->program->PRJGroup             =  'Privilege Group';
$lang->program->PRJCreateGroup       =  'Create Group';
$lang->program->PRJEditGroup         =  'Edit Group';
$lang->program->PRJCopyGroup         =  'Copy Group';
$lang->program->PRJManageView        =  'Manage View';
$lang->program->PRJManagePriv        =  'Manage Privilege';
$lang->program->PRJManageMembers     =  'Manage Team';
$lang->program->PRJExport            =  'Export';
$lang->program->PRJManageGroupMember =  'Manage Group';

/* Fields. */
$lang->program->PRJCommon          = 'Program';
$lang->program->stage              = 'Stage';
$lang->program->PRJName            = 'Name';
$lang->program->PRJTemplate        = 'Template';
$lang->program->PRJCategory        = 'Category';
$lang->program->PRJDesc            = 'Description';
$lang->program->PRJCode            = 'Code';
$lang->program->PRJCopy            = 'Copy';
$lang->program->begin              = 'Begin';
$lang->program->end                = 'End';
$lang->program->PRJStatus          = 'Status';
$lang->program->PRJPM              = 'Manager';
$lang->program->PRJBudget          = 'Budget';
$lang->program->dateRange          = 'Duration';
$lang->program->to                 = ' to ';
$lang->program->realEnd            = 'Actual End';
$lang->program->realBegan          = 'Actual Began';
$lang->program->bygrid             = 'Kanban';
$lang->program->bylist             = 'List';
$lang->program->mine               = 'My';
$lang->program->setPlanduration    = 'Set Duration';
$lang->program->privway            = 'Access Control';
$lang->program->durationEstimation = 'Estimated Workload';
$lang->program->PRJProgress        = 'Program Progress';
$lang->program->teamCount          = 'Man';
$lang->program->leftStories        = 'Left Stories';
$lang->program->leftTasks          = 'Left Tasks';
$lang->program->leftBugs           = 'Left Bugs';
$lang->program->PRJChildren        = 'Child Project';
$lang->program->PRJParent          = 'Parent Project';
$lang->program->allStories         = 'All Stories';
$lang->program->doneStories        = 'Finished Stories';
$lang->program->leftStories        = 'Left Stories';
$lang->program->PRJAllInput        = 'Total Input';
$lang->program->PRJWeekly          = 'Program Weekly';
$lang->program->pv                 = 'PV';
$lang->program->ev                 = 'EV';
$lang->program->sv                 = 'SV%';
$lang->program->ac                 = 'AC';
$lang->program->cv                 = 'CV%';
$lang->program->PRJPm              = 'PM';
$lang->program->PRJTeamCount       = 'Team';
$lang->program->PRJLongTime        = 'Long-Term Project';

$lang->program->unitList[''] = '';
$lang->program->unitList['yuan']   = 'Yuan';
$lang->program->unitList['dollar'] = 'Dollars';

$lang->program->templateList['scrum']     = "Scrum";
$lang->program->templateList['waterfall'] = "CMMI";

$lang->program->categoryList['single']   = "Single product";
$lang->program->categoryList['multiple'] = "Multiple products";

$lang->program->featureBar['all']       = 'All';
$lang->program->featureBar['doing']     = 'Going';
$lang->program->featureBar['wait']      = 'Waiting';
$lang->program->featureBar['suspended'] = 'Suspended';
$lang->program->featureBar['closed']    = 'Closed';

$lang->program->PRJAclList['open']    = "Default (Users who can visit {$lang->projectCommon} can access it)";
$lang->program->PRJAclList['private'] = 'Private (For team members only)';
$lang->program->PRJAclList['custom']  = 'Custom (Team members and the whitelist users can access it)';

$lang->program->PRJPrivwayList['extend'] = 'Inherit ( program privilege and company privilege)';
$lang->program->PRJPrivwayList['reset']  = 'Reset (program privilege only)';

$lang->program->statusList['wait']      = 'Wait';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->PRJNoProgram         = 'No program';
$lang->program->PRJAccessDenied      = 'Access denied to this program';
$lang->program->PRJChooseProgramType = 'Select management type';
$lang->program->nextStep             = 'Next step';
$lang->program->hoursUnit            = '%s hours';
$lang->program->membersUnit          = '%s men';
$lang->program->lastIteration        = 'Recent iterations';
$lang->program->ongoingStage         = 'Ongoing stage';
$lang->program->scrum                = 'Scrum';
$lang->program->scrumTitle           = 'Scrum ALM';
$lang->program->waterfall            = 'CMMI';
$lang->program->waterfallTitle       = 'CMMI Management';
$lang->program->PRJCannotCreateChild = 'The project has contents, so you cannot add a child project. You can create a parent project for this one and then add a child project for the parent project.';
$lang->program->PRJHasChildren       = 'This project has a child project, so it cannot be deleted.';
$lang->program->PRJConfirmDelete     = 'Do you want to delete this project?';
$lang->program->emptyPM              = 'No manager';
$lang->program->PRJCannotChangeToCat = "The project has contents, so you cannot it to a parent project.";
$lang->program->PRJCannotCancelCat   = "There are child projects of this project. You cannot cancel the parent project mark.";
$lang->program->PRJParentBeginEnd    = "The begin and end date of the parent project: %s ~ %s";
$lang->program->PRJParentBudget      = "The budget of the parent project: %s";
$lang->program->PRJBeginLetterParent = "The begin date of the parent project: %s. It cannot be < the begin date of its parent project.";
$lang->program->PRJEndGreaterParent  = "The end date of the parent project: %s. It cannot be > the end date of its parent project.";
$lang->program->PRJBeginGreateChild  = "The earliest begin date of a child project: %s. The begin date of its parent project cannot be > the earliest begin date of a child project.";
$lang->program->PRJEndLetterChild    = "The latest end date of a child project: %s. The end date of its parent project cannot be < the latest end date of child project.";
$lang->program->PRJChildLongTime     = "There are long-term projects in the child project, and the parent project should also be a long-term project.";
