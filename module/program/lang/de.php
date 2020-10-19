<?php
/* Actions. */
$lang->program->createGuide          = 'Select Template';
$lang->program->PRJIndex             = 'Dashboard';
$lang->program->PRJHome              = 'Home';
$lang->program->PRJCreate            = 'Create Project';
$lang->program->PRJEdit              = 'Edit';
$lang->program->PRJBatchEdit         = 'Batch Edit';
$lang->program->PRJBrowse            = 'Projects';
$lang->program->PRJAll               = 'All';
$lang->program->PRJStart             = 'Start';
$lang->program->PRJFinish            = 'Finish';
$lang->program->PRJSuspend           = 'Suspend';
$lang->program->PRJDelete            = 'Delete';
$lang->program->PRJClose             = 'Close';
$lang->program->PRJActivate          = 'Activate';
$lang->program->PRJGroup             = 'Privilege Group';
$lang->program->PRJCreateGroup       = 'Create Group';
$lang->program->PRJEditGroup         = 'Edit Group';
$lang->program->PRJCopyGroup         = 'Copy Group';
$lang->program->PRJManageView        = 'Manage View';
$lang->program->PRJManagePriv        = 'Manage Privilege';
$lang->program->PRJManageMembers     = 'Manage Team';
$lang->program->export               = 'Export';
$lang->program->PRJManageGroupMember = 'Manage Group';
$lang->program->PRJModuleSetting     = 'Program Settings';
$lang->program->PRJModuleOpen        = 'Program Name';
$lang->program->PRJUpdateOrder       = 'Order';
$lang->program->PRJSort              = 'Order';

/* Fields. */
$lang->program->common             = 'Project';
$lang->program->stage              = 'Stage';
$lang->program->PRJName            = 'Name';
$lang->program->PRJModel           = 'Template';
$lang->program->PRJCategory        = 'Category';
$lang->program->PRJDesc            = 'Description';
$lang->program->PRJCode            = 'Code';
$lang->program->PRJCopy            = 'Copy';
$lang->program->begin              = 'Begin';
$lang->program->end                = 'End';
$lang->program->PRJStatus          = 'Status';
$lang->program->PRJPM              = 'Manager';
$lang->program->PRJBudget          = 'Budget';
$lang->program->PRJTemplate        = 'Template';
$lang->program->PRJEstimate        = 'Estimates';
$lang->program->PRJConsume         = 'Cost';
$lang->program->PRJSurplus         = 'Left';
$lang->program->PRJProgress        = 'Progress';
$lang->program->dateRange          = 'Duration';
$lang->program->to                 = ' to ';
$lang->program->realEnd            = 'Actual End';
$lang->program->realBegan          = 'Actual Began';
$lang->program->bygrid             = 'Kanban';
$lang->program->bylist             = 'List';
$lang->program->mine               = 'My';
$lang->program->setPlanduration    = 'Set Duration';
$lang->program->auth               = 'Access Control';
$lang->program->durationEstimation = 'Estimated Workload';
$lang->program->teamCount          = 'Man';
$lang->program->leftStories        = 'Left Stories';
$lang->program->leftTasks          = 'Left Tasks';
$lang->program->leftBugs           = 'Left Bugs';
$lang->program->PRJChildren        = 'Child Project';
$lang->program->PRJParent          = 'Parent Project';
$lang->program->allStories         = 'All Stories';
$lang->program->doneStories        = 'Finished Stories';
$lang->program->allInput           = 'Total Input';
$lang->program->weekly             = 'Program Weekly';
$lang->program->pv                 = 'PV';
$lang->program->ev                 = 'EV';
$lang->program->sv                 = 'SV%';
$lang->program->ac                 = 'AC';
$lang->program->cv                 = 'CV%';
$lang->program->PRJTeamCount       = 'Team';
$lang->program->PRJLongTime        = 'Long-Term Project';

$lang->program->unitList['']       = '';
$lang->program->unitList['yuan']   = 'Yuan';
$lang->program->unitList['dollar'] = 'Dollars';

$lang->program->modelList['scrum']     = "Scrum";
$lang->program->modelList['waterfall'] = "CMMI";

$lang->program->PRJCategoryList['single']   = "Single product";
$lang->program->PRJCategoryList['multiple'] = "Multiple products";

$lang->program->PRJLifeTimeList['short'] = "Short-Term";
$lang->program->PRJLifeTimeList['long']  = "Long-Term";
$lang->program->PRJLifeTimeList['ops']   = "DevOps";

$lang->program->featureBar['all']       = 'All';
$lang->program->featureBar['doing']     = 'Going';
$lang->program->featureBar['wait']      = 'Waiting';
$lang->program->featureBar['suspended'] = 'Suspended';
$lang->program->featureBar['closed']    = 'Closed';

$lang->program->PRJAclList['open']    = "Default (Users who can visit {$lang->projectCommon} can access it)";
$lang->program->PRJAclList['private'] = 'Private (For team members only)';
$lang->program->PRJAclList['custom']  = 'Custom (Team members and the whitelist users can access it)';

$lang->program->PRJPrivwayList['extend'] = 'Inherit (program privilege and company privilege)';
$lang->program->PRJPrivwayList['reset']  = 'Reset (program privilege only)';

$lang->program->statusList['wait']      = 'Waiting';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->noPRJ             = 'No project.';
$lang->program->accessDenied      = 'Access denied!';
$lang->program->chooseProgramType = 'Select a method';
$lang->program->nextStep          = 'Next step';
$lang->program->hoursUnit         = '%s hours';
$lang->program->membersUnit       = '%s men';
$lang->program->lastIteration     = 'Recent iterations';
$lang->program->ongoingStage      = 'Ongoing stage';
$lang->program->scrum             = 'Scrum';
$lang->program->waterfall         = 'CMMI';
$lang->program->waterfallTitle    = 'CMMI';
$lang->program->cannotCreateChild = 'It is not empty, so you cannot add a child. You can add a parent for it, and then create a child.';
$lang->program->hasChildren       = 'It has child projects. You cannot delete it.';
$lang->program->confirmDelete     = "Do you want to delete [%s]?";
$lang->program->emptyPM           = 'No manager';
$lang->program->cannotChangeToCat = "It is not empty, so you cannot change it to a parent.";
$lang->program->cannotCancelCat   = "It has child projects, so you cannot unmark the parent.";
$lang->program->parentBeginEnd    = "Parent begin&end date: %s ~ %s";
$lang->program->parentBudget      = "Parent budget: %s";
$lang->program->beginLetterParent = "Parent begin date: %s, begin date should be >= parent begin date.";
$lang->program->endGreaterParent  = "Parent end date: %s, end date should be <= parent end date.";
$lang->program->beginGreateChild  = "Child earliest begin: %s, parent begin date <= child earliest begin date.";
$lang->program->endLetterChild    = "Child latest end: %s, parent end date >= child latest end date.";
$lang->program->childLongTime     = "If a child as long-term projects, the parent should be long-term too.";
$lang->program->readjustTime      = 'Change the project begin&end date.';

$lang->program->PRJProgramTitle['0']    = 'Hide';
$lang->program->PRJProgramTitle['base'] = 'Base-level program only';
$lang->program->PRJProgramTitle['end']  = 'End-level program only';

$lang->program->PRJAccessDenied      = 'Access denied to this program';
$lang->program->PRJChooseProgramType = 'Select management type';
$lang->program->scrumTitle           = 'Agile Development Management';
$lang->program->PRJCannotCreateChild = 'The project has contents, so you cannot add a child project. You can create a parent project for this one and then add a child project for the parent project.';
$lang->program->PRJHasChildren       = 'This project has a child project, so it cannot be deleted.';
$lang->program->PRJConfirmDelete     = 'Do you want to delete this project?';
$lang->program->PRJCannotChangeToCat = "The project has contents, so you cannot it to a parent project.";
$lang->program->PRJCannotCancelCat   = "There are child projects of this project. You cannot cancel the parent project mark.";
$lang->program->PRJParentBeginEnd    = "The begin and end date of the parent project: %s ~ %s";
$lang->program->PRJParentBudget      = "The budget of the parent project: %s";
$lang->program->PRJBeginLetterParent = "The begin date of the parent project: %s. It cannot be < the begin date of its parent project.";
$lang->program->PRJEndGreaterParent  = "The end date of the parent project: %s. It cannot be > the end date of its parent project.";
$lang->program->PRJBeginGreateChild  = "The earliest begin date of a child project: %s. The begin date of its parent project cannot be > the earliest begin date of a child project.";
$lang->program->PRJEndLetterChild    = "The latest end date of a child project: %s. The end date of its parent project cannot be < the latest end date of child project.";
$lang->program->PRJChildLongTime     = "There are long-term projects in the child project, and the parent project should also be a long-term project.";

/* Actions. */
$lang->program->PGMCommon            = 'Program';
$lang->program->PGMIndex             = 'Home';
$lang->program->PGMCreate            = 'Create Program';
$lang->program->PGMCreateGuide       = 'Select Template';
$lang->program->PGMEdit              = 'Edit Program';
$lang->program->PGMBrowse            = 'Programs';
$lang->program->PGMProduct           = 'Products';
$lang->program->PGMProject           = 'Projects';
$lang->program->PGMAll               = 'All Programs';
$lang->program->PGMStart             = 'Start';
$lang->program->PGMFinish            = 'Finish';
$lang->program->PGMSuspend           = 'Suspend';
$lang->program->PGMDelete            = 'Delete';
$lang->program->PGMClose             = 'Close';
$lang->program->PGMView              = 'Overview';
$lang->program->PGMActivate          = 'Activate';
$lang->program->PGMGroup             = 'Privilege Group';
$lang->program->PGMCreateGroup       = 'Create Group';
$lang->program->PGMEditGroup         = 'Edit Group';
$lang->program->PGMCopyGroup         = 'Copy Group';
$lang->program->PGMManageView        = 'Manage View';
$lang->program->PGMManagePriv        = 'Manage Privilege';
$lang->program->PGMManageMembers     = 'Program Team';
$lang->program->PGMExport            = 'Export';
$lang->program->PGMManageGroupMember = 'Manage Group';

/* Fields. */
$lang->program->PGMName      = 'Name';
$lang->program->PGMTemplate  = 'Template';
$lang->program->PGMCategory  = 'Type';
$lang->program->PGMDesc      = 'Description';
$lang->program->PGMCode      = 'Code';
$lang->program->PGMCopy      = 'Copy Program';
$lang->program->PGMStatus    = 'Status';
$lang->program->PGMPM        = 'Manager';
$lang->program->PGMBudget    = 'Budget';
$lang->program->PGMProgress  = 'Progress';
$lang->program->PGMChildren  = 'Child';
$lang->program->PGMParent    = 'Parent';
$lang->program->PGMAllInput  = 'Input';
$lang->program->PGMTeamCount = 'Team';
$lang->program->PGMLongTime  = 'Long-Term';

$lang->program->noPGM         = 'No program.';
$lang->program->PGMShowClosed = 'Closed programs.';i 

$lang->program->PGMAclList['open']    = "Default(user who can visit the program can access it)";
$lang->program->PGMAclList['private'] = "Private(for the program team only)";
$lang->program->PGMAclList['custom']  = "Custom(the program team and the whitelist users can access it)";

$lang->program->PGMAuthList['extend'] = 'Inherit(program privileges and company privileges)';
$lang->program->PGMAuthList['reset']  = 'Reset(program privileges only)';

$lang->program->PGMFeatureBar['all'] = 'All';
