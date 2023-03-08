<?php
/* Fields. */
$lang->program->id             = 'ID';
$lang->program->name           = 'Name';
$lang->program->template       = 'Template';
$lang->program->category       = 'Type';
$lang->program->desc           = 'Description';
$lang->program->status         = 'Status';
$lang->program->PM             = 'Manager';
$lang->program->budget         = 'Budget';
$lang->program->budgetUnit     = 'Budget Unit';
$lang->program->begin          = 'Begin';
$lang->program->end            = 'End';
$lang->program->realBegin      = 'Actual Begin';
$lang->program->realEnd        = 'Actual End';
$lang->program->stage          = 'Stage';
$lang->program->type           = 'Type';
$lang->program->pri            = 'Priority';
$lang->program->parent         = 'Parent';
$lang->program->exchangeRate   = 'Exchange Rate';
$lang->program->openedBy       = 'OpenedBy';
$lang->program->openedDate     = 'OpenedDate';
$lang->program->closedBy       = 'ClosedBy';
$lang->program->closedDate     = 'ClosedDate';
$lang->program->canceledBy     = 'CanceledBy';
$lang->program->canceledDate   = 'CanceledDate';
$lang->program->lastEditedDate = 'LastEditedDate';
$lang->program->suspendedDate  = 'SuspendedDate';
$lang->program->vision         = 'Vision';
$lang->program->team           = 'Team';
$lang->program->order          = 'Rank';
$lang->program->days           = 'Days';
$lang->program->acl            = 'Access Control';
$lang->program->whitelist      = 'WhiteList';
$lang->program->deleted        = 'Deleted';
$lang->program->lifetime       = 'Lifetime';
$lang->program->output         = 'Output';
$lang->program->auth           = 'Auth';
$lang->program->path           = 'Path';
$lang->program->grade          = 'Grade';
$lang->program->realBegan      = 'RealBegan';
$lang->program->realEnd        = 'RealEnd';
$lang->program->version        = 'Version';
$lang->program->parentVersion  = 'ParentVersion';
$lang->program->planDuration   = 'PlanDuration';
$lang->program->realDuration   = 'RealDuration';
$lang->program->openedVersion  = 'OpenedVersion';
$lang->program->lastEditedBy   = 'LastEditedBy';
$lang->program->lastEditedDate = 'LastEditedDate';
$lang->program->childProgram   = 'Child Program';
$lang->program->ignore         = 'Ignore';

/* Actions. */
$lang->program->common                  = 'Program';
$lang->program->index                   = 'Home';
$lang->program->create                  = 'Create Program';
$lang->program->createGuide             = 'Select Template';
$lang->program->edit                    = 'Edit Program';
$lang->program->browse                  = 'Programs';
$lang->program->kanbanAction            = 'Kanban';
$lang->program->view                    = 'Program Detail';
$lang->program->copy                    = 'Copy Program';
$lang->program->product                 = "{$lang->productCommon}s";
$lang->program->project                 = "Program {$lang->projectCommon} List";
$lang->program->all                     = 'All Programs';
$lang->program->start                   = 'Start';
$lang->program->finish                  = 'Finish';
$lang->program->suspend                 = 'Suspend';
$lang->program->delete                  = 'Delete';
$lang->program->close                   = 'Close';
$lang->program->activate                = 'Activate';
$lang->program->export                  = 'Export';
$lang->program->stakeholder             = 'Stakeholder';
$lang->program->createStakeholder       = 'Create Stakeholder';
$lang->program->unlinkStakeholder       = 'Unlink Stakeholder';
$lang->program->batchUnlinkStakeholders = 'Batch Remove Stakeholder';
$lang->program->unlink                  = 'Unlink';
$lang->program->updateOrder             = 'Rank';
$lang->program->unbindWhitelist         = 'Unbind Whitelist';
$lang->program->importStakeholder       = 'Import from program';
$lang->program->manageMembers           = 'Program Team';
$lang->program->confirmChangePRJUint    = "Synchronize the budget unit of the subprograms and the {$lang->projectCommon}s of the program? If yes, please the current exchange rate.";
$lang->program->exRateNotNegative       = 'The『exchange rate』should not be negative.';
$lang->program->changePRJUnit           = 'Update the budget unit of the ' . $lang->projectCommon;
$lang->program->showNotCurrentProjects  = "Display {$lang->projectCommon} information of non current program";

$lang->program->progress         = 'Progress';
$lang->program->children         = 'Add Child';
$lang->program->allInvest        = 'Input';
$lang->program->teamCount        = 'Team';
$lang->program->longTime         = 'Long-Term';
$lang->program->moreProgram      = 'More program';
$lang->program->stakeholderType  = 'Stakeholder type';
$lang->program->parentBudget     = 'Parent program surplus budget：';
$lang->program->isStakeholderKey = 'Key stakeholder';
$lang->program->summary          = "This page contains %d top programs and %d independent {$lang->projectCommon}s.";

$lang->program->stakeholderTypeList['inside']  = 'Inside';
$lang->program->stakeholderTypeList['outside'] = 'Outside';

$lang->program->noProgram          = 'No program.';
$lang->program->showClosed         = 'Closed';
$lang->program->tips               = "If a parent program is selected, the {$lang->productCommon}s under the parent program can be associated. If no program is selected for the {$lang->projectCommon}, a {$lang->productCommon} with the same name as the {$lang->projectCommon} is created and associated with the {$lang->projectCommon} by default.";
$lang->program->confirmBatchUnlink = "Do you want to batch unlink these stakeholders?";
$lang->program->beginLetterParent  = 'The start date of the program is less than the start date of the parent program:';
$lang->program->endGreaterParent   = 'The finish date of the program is greater than the finish date of the parent program:';
$lang->program->dateExceedParent   = 'The start and finish date of the program was greater than the start and finish date of the parent program:';
$lang->program->beginGreateChild   = "The start date of the program is greater than the minimum start date of the subprogram or {$lang->projectCommon}:";
$lang->program->endLetterChild     = "The finish date of the program is less than the maximum finish date of the subprogram or {$lang->projectCommon}:";
$lang->program->dateExceedChild    = "The start and finish date of the program no longer include the date scope of the subprogram or {$lang->projectCommon}:";
$lang->program->closeErrorMessage  = "There are subprograms or {$lang->projectCommon}s that are not closed";
$lang->program->hasChildren        = "The program has a child program or the {$lang->projectCommon} exists and can not be deleted.";
$lang->program->hasProduct         = "The program has {$lang->productCommon}s exist and can not be deleted.";
$lang->program->confirmDelete      = 'Do you want to delete the \"%s\" Program?';
$lang->program->confirmUnlink      = 'Do you want to remove the Stakeholder?';
$lang->program->readjustTime       = 'Change the program begin&end date.';
$lang->program->accessDenied       = 'You have no access to the program.';
$lang->program->beyondParentBudget = 'The remaining budget of the owned program has been exceeded.';
$lang->program->checkedProjects    = 'Seleted %s items';
$lang->program->budgetOverrun      = "The program's budget exceeds the remaining budget of the parent program:";

$lang->program->endList[31]  = 'One month';
$lang->program->endList[93]  = 'Trimester';
$lang->program->endList[186] = 'Half year';
$lang->program->endList[365] = 'One year';
$lang->program->endList[999] = 'Longtime';

$lang->program->aclList['private'] = "Private (accessible to {$lang->projectCommon} portfolio holders and stakeholders, stakeholders can follow up maintenance)";
$lang->program->aclList['open']    = "Default(user who can visit the program can access it)";

$lang->program->subAclList['private'] = "Private (accessible to this program set leader and stakeholders, stakeholders can follow up maintenance)";
$lang->program->subAclList['open']    = "Default (user who can visit the program can access it)";
$lang->program->subAclList['program'] = "Open within the program (accessible to all higher-level program directors and affiliates, as well as to this program director and affiliates)";

$lang->program->subAcls['private'] = 'Private';
$lang->program->subAcls['open']    = 'Default';
$lang->program->subAcls['program'] = 'Open within the program';

$lang->program->authList['extend'] = 'Inherit (program privileges and company privileges)';
$lang->program->authList['reset']  = 'Reset (program privileges only)';

$lang->program->statusList['wait']      = 'Waiting';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->featureBar['browse']['all']       = 'All';
$lang->program->featureBar['browse']['unclosed']  = 'Unclosed';
$lang->program->featureBar['browse']['wait']      = 'Waiting';
$lang->program->featureBar['browse']['doing']     = 'Doing';
$lang->program->featureBar['browse']['suspended'] = 'Suspended';
$lang->program->featureBar['browse']['closed']    = 'Closed';

$lang->program->featureBar['product']['all']      = 'All';
$lang->program->featureBar['product']['noclosed'] = 'Open';
$lang->program->featureBar['product']['closed']   = 'Closed';

$lang->program->featureBar['project']['all']       = 'All';
$lang->program->featureBar['project']['unclosed']  = 'Unclosed';
$lang->program->featureBar['project']['wait']      = 'Waiting';
$lang->program->featureBar['project']['doing']     = 'Doing';
$lang->program->featureBar['project']['suspended'] = 'Suspended';
$lang->program->featureBar['project']['closed']    = 'Closed';

$lang->program->kanban = new stdclass();
$lang->program->kanban->common             = 'Program Kanban';
$lang->program->kanban->typeList['my']     = 'My Programs';
$lang->program->kanban->typeList['others'] = 'Others';

$lang->program->kanban->openProducts    = "Open {$lang->productCommon}s";
$lang->program->kanban->unexpiredPlans  = 'Unexpired Plans';
$lang->program->kanban->waitingProjects = "Waiting {$lang->projectCommon}s";
$lang->program->kanban->doingProjects   = "Ongoing {$lang->projectCommon}s";
$lang->program->kanban->doingExecutions = 'Ongoing Executions';
$lang->program->kanban->normalReleases  = 'Normal Releases';

$lang->program->kanban->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$lang->program->defaultProgram = 'Default program';
