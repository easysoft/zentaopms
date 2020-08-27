<?php
/* Actions. */
$lang->program->index              = 'Project';
$lang->program->create             = 'Create';
$lang->program->createGuide        = 'Select The Project Template';
$lang->program->edit               = 'Edit';
$lang->program->browse             = 'Project List';
$lang->program->all                = 'All';
$lang->program->start              = 'Start';
$lang->program->finish             = 'Finish';
$lang->program->suspend            = 'Suspend';
$lang->program->delete             = 'Delete';
$lang->program->close              = 'Close';
$lang->program->activate           = 'Activate';
$lang->program->group              = 'Group';
$lang->program->createGroup        = 'Create Group';
$lang->program->editGroup          = 'Edit Group';
$lang->program->copyGroup          = 'Copy Group';
$lang->program->manageView         = 'Manage View';
$lang->program->managePriv         = 'Manage Priv';
$lang->program->manageMembers      = 'Project Team';
$lang->program->export             = 'Export';
$lang->program->manageGroupMember  = 'Manage Grop';

/* Fields. */
$lang->program->common             = 'Project';
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
$lang->program->PM                 = 'Project Manager';
$lang->program->budget             = 'Budget';
$lang->program->dateRange          = 'Date Range';
$lang->program->to                 = ' to ';
$lang->program->realFinished       = 'Real finished';
$lang->program->realStarted        = 'Real started';
$lang->program->bygrid             = 'Grid';
$lang->program->bylist             = 'List';
$lang->program->mine               = 'Participated';
$lang->program->setPlanduration    = 'Set Planduration';
$lang->program->privway            = 'Priv Way';
$lang->program->durationEstimation = 'Duration Estimation';
$lang->program->progress           = 'Program Progress';
$lang->program->teamCount          = 'The Number Of Team';
$lang->program->leftStories        = 'Left Stories';
$lang->program->leftTasks          = 'Left Tasks';
$lang->program->leftBugs           = 'Left Bugs';
$lang->program->children           = 'Subprogram';
$lang->program->parent             = 'Parent Program';
$lang->program->allStories         = 'All stories';
$lang->program->doneStories        = 'Done stories';
$lang->program->leftStories        = 'Left stories';
$lang->program->allInput           = 'All Input';
$lang->program->weekly             = 'Program Weekly';
$lang->program->pv                 = 'PV';
$lang->program->ev                 = 'EV';
$lang->program->sv                 = 'SV%';
$lang->program->ac                 = 'AC';
$lang->program->cv                 = 'CV%';
$lang->program->pm                 = 'PM';
$lang->program->teamCount          = 'The Number Of Team';

$lang->program->unitList[''] = '';
$lang->program->unitList['yuan']   = 'Yuan';
$lang->program->unitList['dollar'] = 'Dollars';

$lang->program->templateList['scrum'] = "Scrum";
$lang->program->templateList['cmmi']  = "CMMI";

$lang->program->categoryList['single']   = "Single product";
$lang->program->categoryList['multiple'] = "Multiple products";

$lang->program->featureBar['all']       = 'All';
$lang->program->featureBar['doing']     = 'Going';
$lang->program->featureBar['wait']      = 'Waiting';
$lang->program->featureBar['suspended'] = 'Suspended';
$lang->program->featureBar['closed']    = 'Closed';

$lang->program->aclList['open']    = "Default (Users who can visit {$lang->projectCommon} can access it.)";
$lang->program->aclList['private'] = 'Private (For team members only.)';
$lang->program->aclList['custom']  = 'Custom (Team members and the whitelist users can access it.)';

$lang->program->privwayList['extend'] = 'Extend(Mix program priv and common priv.)';
$lang->program->privwayList['reset']  = 'Reset(Only program prive.)';

$lang->program->statusList['wait']      = 'Wait';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->noProgram          = 'No Program';
$lang->program->accessDenied       = 'Has No Access To The Program';
$lang->program->chooseProgramType  = 'Choose management type';
$lang->program->nextStep           = 'Next step';
$lang->program->hoursUnit          = '%s hours';
$lang->program->membersUnit        = '%s members';
$lang->program->lastIteration      = 'Latest iterations';
$lang->program->ongoingStage       = 'Ongoing stage';
$lang->program->scrum              = 'Scrum';
$lang->program->scrumTitle         = 'Scrum development full process project management';
$lang->program->cmmi               = 'CMMI';
$lang->program->cmmiTitle          = 'CMMI management';
$lang->program->cannotCreateChild  = 'The project already has actual content and can not add subprojects directly. You can create a parent project for the current project and then add a child project under the new parent project.';
$lang->program->hasChildren        = 'This project has a subproject and can not be deleted.';
$lang->program->confirmDelete      = 'Do you want to delete this project?';
$lang->program->emptyPM            = 'No program manager';
$lang->program->cannotChangeToCat  = "The project already has the actual content and can not be modified as a parent project";
$lang->program->cannotCancelCat    = "There are already children under this project. You can not unmark the parent project";
$lang->program->parentBeginEnd     = "Begin and end date of parent project: %s ~ %s";
$lang->program->parentBudget       = "Parent project budget: %s";
$lang->program->beginLetterParent  = "Begin date of parent project: %s, Begin date cannot be less than the begin date of parent project.";
$lang->program->endGreaterParent   = "End date of parent project: %s, End date cannot be greater than the end date of parent project.";
$lang->program->beginGreateChild   = "Minimum begin date of child project: %s, the begin date of parent project cannot be greater than the minimum begin date of child project.";
$lang->program->endLetterChild     = "Maximum end date of child project: %s, the end date of parent project cannot be less than the maximum end date of child project.";
