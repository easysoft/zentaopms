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
$lang->program->realEnd        = 'Actual End';
$lang->program->version        = 'Version';
$lang->program->parentVersion  = 'ParentVersion';
$lang->program->planDuration   = 'PlanDuration';
$lang->program->realDuration   = 'RealDuration';
$lang->program->openedVersion  = 'OpenedVersion';
$lang->program->lastEditedBy   = 'LastEditedBy';
$lang->program->lastEditedDate = 'LastEditedDate';
$lang->program->childProgram   = 'sous-programme';
$lang->program->ignore         = 'Ignorer';

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
$lang->program->product                 = 'Products';
$lang->program->project                 = 'Program Project List';
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
$lang->program->importStakeholder       = 'Import program';
$lang->program->manageMembers           = 'Program Team';
$lang->program->confirmChangePRJUint    = 'Synchronize the budget unit of the subprograms and the projects of the program? If yes, please the current exchange rate.';
$lang->program->exRateNotNegative       = 'The『exchange rate』should not be negative.';
$lang->program->changePRJUnit           = 'Update the budget unit of the project';

$lang->program->progress         = 'Progress';
$lang->program->children         = 'Child';
$lang->program->allInvest        = 'Input';
$lang->program->teamCount        = 'Team';
$lang->program->longTime         = 'Long-Term';
$lang->program->moreProgram      = 'More Programs';
$lang->program->stakeholderType  = 'Stakeholder type';
$lang->program->parentBudget     = 'Parent program surplus budget：';
$lang->program->isStakeholderKey = 'Key stakeholder';
$lang->program->summary          = 'This page contains %d top programs and %d independent projects.';

$lang->program->stakeholderTypeList['inside']  = 'Inside';
$lang->program->stakeholderTypeList['outside'] = 'Outside';

$lang->program->noProgram          = 'No program.';
$lang->program->showClosed         = 'Closed programs.';
$lang->program->tips               = 'If a parent program is selected, the products under the parent program can be associated. If no program is selected for the project, a product with the same name as the project is created and associated with the project by default.';
$lang->program->confirmBatchUnlink = "Do you want to batch unlink these stakeholders?";
$lang->program->beginLetterParent  = 'La date de début du programme était < à la date de début du programme parent:';
$lang->program->endGreaterParent   = 'La date de fin du programme était > à la date de fin du programme parent:';
$lang->program->dateExceedParent   = 'La date de début et de fin du programme était > à la date de début et de fin du programme parent:';
$lang->program->beginGreateChild   = 'La date de début du programme était > à la date minimale de début du sous-programme ou du projet:';
$lang->program->endLetterChild     = 'La date de fin du programme était < à la de fin maximale du sous-programme ou du projet:';
$lang->program->dateExceedChild    = 'La date de début et de fin du programme ne correspond plus la date du sous-programme ou du projet:';
$lang->program->closeErrorMessage  = 'There are subprograms or projects that are not closed';
$lang->program->hasChildren        = 'It has child programs or projects. You cannot delete it.';
$lang->program->hasProduct         = 'It has products. You cannot delete it.';
$lang->program->confirmDelete      = "Do you want to delete it?";
$lang->program->confirmUnlink      = 'Voulez - vous retirer les intervenants?';
$lang->program->readjustTime       = 'Change the program begin&end date.';
$lang->program->accessDenied       = 'You have no access to the program.';
$lang->program->beyondParentBudget = 'The remaining budget of the owned program has been exceeded.';
$lang->program->checkedProjects    = "Pour s électionner l'élément% s";
$lang->program->budgetOverrun      = "Le budget du programme a dépassé le budget restant du programme parent:";

$lang->program->endList[31]  = 'One month';
$lang->program->endList[93]  = 'Trimester';
$lang->program->endList[186] = 'Half year';
$lang->program->endList[365] = 'One year';
$lang->program->endList[999] = 'Longtime';

$lang->program->aclList['private'] = "Private (accessible to project portfolio holders and stakeholders, stakeholders can follow up maintenance)";
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
$lang->program->featureBar['browse']['wait']      = 'En Attente';
$lang->program->featureBar['browse']['doing']     = 'En Cours';
$lang->program->featureBar['browse']['suspended'] = 'Suspendues';
$lang->program->featureBar['browse']['closed']    = 'Fermées';

$lang->program->kanban = new stdclass();
$lang->program->kanban->common             = 'Program Kanban';
$lang->program->kanban->typeList['my']     = 'My Programs';
$lang->program->kanban->typeList['others'] = 'Others';

$lang->program->kanban->openProducts    = 'Open Products';
$lang->program->kanban->unexpiredPlans  = 'Unexpired Plans';
$lang->program->kanban->waitingProjects = 'Waiting Projects';
$lang->program->kanban->doingProjects   = 'Ongoing Projects';
$lang->program->kanban->doingExecutions = 'Ongoing Executions';
$lang->program->kanban->normalReleases  = 'Normal Releases';

$lang->program->kanban->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');
