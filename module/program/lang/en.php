<?php
/* Actions. */
$lang->program->index             = 'Program';
$lang->program->create            = 'Create Program';
$lang->program->createGuide       = 'Select Template';
$lang->program->edit              = 'Edit';
$lang->program->browse            = 'Programs';
$lang->program->all               = 'All';
$lang->program->start             = 'Start';
$lang->program->finish            = 'Finish';
$lang->program->suspend           = 'Suspend';
$lang->program->delete            = 'Delete';
$lang->program->close             = 'Close';
$lang->program->activate          = 'Activate';
$lang->program->group             = 'Privilege Group';
$lang->program->createGroup       = 'Create Group';
$lang->program->editGroup         = 'Edit Group';
$lang->program->copyGroup         = 'Copy Group';
$lang->program->manageView        = 'Manage View';
$lang->program->managePriv        = 'Manage Privilege';
$lang->program->manageMembers     = 'Manage Team';
$lang->program->export            = 'Export';
$lang->program->manageGroupMember = 'Manage Group';

/* Fields. */
$lang->program->common             = 'Program';
$lang->program->stage              = 'Stage';
$lang->program->name               = 'Name';
$lang->program->template           = 'Template';
$lang->program->category           = 'Category';
$lang->program->desc               = 'Description';
$lang->program->code               = 'Code';
$lang->program->copy               = 'Copy';
$lang->program->begin              = 'Begin';
$lang->program->end                = 'End';
$lang->program->status             = 'Status';
$lang->program->PM                 = 'Manager';
$lang->program->budget             = 'Budget';
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
$lang->program->progress           = 'Program Progress';
$lang->program->teamCount          = 'Man';
$lang->program->leftStories        = 'Left Stories';
$lang->program->leftTasks          = 'Left Tasks';
$lang->program->leftBugs           = 'Left Bugs';
$lang->program->children           = 'Child Project';
$lang->program->parent             = 'Parent Project';
$lang->program->allStories         = 'All Stories';
$lang->program->doneStories        = 'Finished Stories';
$lang->program->leftStories        = 'Left Stories';
$lang->program->allInput           = 'Total Input';
$lang->program->weekly             = 'Program Weekly';
$lang->program->pv                 = 'PV';
$lang->program->ev                 = 'EV';
$lang->program->sv                 = 'SV%';
$lang->program->ac                 = 'AC';
$lang->program->cv                 = 'CV%';
$lang->program->pm                 = 'PM';
$lang->program->teamCount          = 'Team';
$lang->program->longTime           = 'Long-Term Project';

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

$lang->program->aclList['open']    = "Default (Users who can visit {$lang->projectCommon} can access it)";
$lang->program->aclList['private'] = 'Private (For team members only)';
$lang->program->aclList['custom']  = 'Custom (Team members and the whitelist users can access it)';

$lang->program->privwayList['extend'] = 'Inherit ( program privilege and company privilege)';
$lang->program->privwayList['reset']  = 'Reset (program privilege only)';

$lang->program->statusList['wait']      = 'Wait';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->noProgram         = 'No program';
$lang->program->accessDenied      = 'Access denied to this program';
$lang->program->chooseProgramType = 'Select management type';
$lang->program->nextStep          = 'Next step';
$lang->program->hoursUnit         = '%s hours';
$lang->program->membersUnit       = '%s men';
$lang->program->lastIteration     = 'Recent iterations';
$lang->program->ongoingStage      = 'Ongoing stage';
$lang->program->scrum             = 'Scrum';
$lang->program->scrumTitle        = 'Scrum ALM';
$lang->program->waterfall         = 'CMMI';
$lang->program->waterfallTitle    = 'CMMI Management';
$lang->program->cannotCreateChild = 'The project has contents, so you cannot add a child project. You can create a parent project for this one and then add a child project for the parent project.';
$lang->program->hasChildren       = 'This project has a child project, so it cannot be deleted.';
$lang->program->confirmDelete     = 'Do you want to delete this project?';
$lang->program->emptyPM           = 'No manager';
$lang->program->cannotChangeToCat = "The project has contents, so you cannot it to a parent project.";
$lang->program->cannotCancelCat   = "There are child projects of this project. You cannot cancel the parent project mark.";
$lang->program->parentBeginEnd    = "The begin and end date of the parent project: %s ~ %s";
$lang->program->parentBudget      = "The budget of the parent project: %s";
$lang->program->beginLetterParent = "The begin date of the parent project: %s. It cannot be < the begin date of its parent project.";
$lang->program->endGreaterParent  = "The end date of the parent project: %s. It cannot be > the end date of its parent project.";
$lang->program->beginGreateChild  = "The earliest begin date of a child project: %s. The begin date of its parent project cannot be > the earliest begin date of a child project.";
$lang->program->endLetterChild    = "The latest end date of a child project: %s. The end date of its parent project cannot be < the latest end date of child project.";
$lang->program->childLongTime     = "There are long-term projects in the child project, and the parent project should also be a long-term project.";
