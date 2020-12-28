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
$lang->program->addProduct           = 'Add Product';
$lang->program->PRJManageGroupMember = 'Manage Group';
$lang->program->PRJModuleSetting     = 'List Settings';
$lang->program->PRJModuleOpen        = 'Program Name';
$lang->program->PRJUpdateOrder       = 'Order';
$lang->program->PRJSort              = 'Order';
$lang->program->PRJWhitelist         = 'Project Whitelist';
$lang->program->PRJAddWhitelist      = 'Project Add Whitelist';
$lang->program->PRJStoryConcept      = 'Story Concept';
$lang->program->unbindWhielist       = 'Project Remove Whitelist';
$lang->program->PRJManageProducts    = 'Manage Products';
$lang->program->view                 = 'Program Detail';
$lang->program->copyTitle            = 'Please select an project to copy';
$lang->program->errorSameProducts    = 'Project cannot be associated with multiple identical products.';
$lang->program->errorNoProducts      = 'At least one product is associated';
$lang->program->copyNoProject        = 'There are no items available to copy.';
$lang->project->searchByName         = 'Enter the project name to retrieve';

/* Fields. */
$lang->program->common             = 'Program';
$lang->program->project            = 'Project';
$lang->program->stage              = 'Stage';
$lang->program->PM                 = 'Manager';
$lang->program->PRJName            = 'Name';
$lang->program->PRJPGM             = 'Program';
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
$lang->program->future             = 'TBD';

$lang->program->productNotEmpty  = 'Please link products or create products.';
$lang->program->existProductName = 'Product name already exists.';

$lang->program->unitList['']        = '';
$lang->program->unitList['wanyuan'] = 'Wanyuan';
$lang->program->unitList['dollar']  = 'Dollars';

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

$lang->program->PRJAclList['open']    = "Open(accessible with project view permissions)";
$lang->program->PRJAclList['private'] = 'Private (For team members and stakeholders only)';

$lang->program->PGMPRJAclList['open']    = "Full disclosure (accessible with project view permissions)";
$lang->program->PGMPRJAclList['program'] = "Public within the project set (accessible to all higher-level project set leaders and stakeholders, members of this project team and stakeholders)";
$lang->program->PGMPRJAclList['private'] = "Private (For team members and stakeholders only)";

$lang->program->PRJAuthList['extend'] = 'Inherit (system privilege and project privilege)';
$lang->program->PRJAuthList['reset']  = 'Reset (project privilege only)';

$lang->program->statusList['wait']      = 'Waiting';
$lang->program->statusList['doing']     = 'Doing';
$lang->program->statusList['suspended'] = 'Suspended';
$lang->program->statusList['closed']    = 'Closed';

$lang->program->endList[31]  = 'One month';
$lang->program->endList[93]  = 'Trimester';
$lang->program->endList[186] = 'Half yearly';
$lang->program->endList[365] = 'One year';
$lang->program->endList[999] = 'Longtime';

$lang->program->noPRJ             = 'No project.';
$lang->program->accessDenied      = 'Access denied!';
$lang->program->chooseProgramType = 'Select the project management model';
$lang->program->nextStep          = 'Next step';
$lang->program->hoursUnit         = '%s hours';
$lang->program->membersUnit       = '%s men';
$lang->program->lastIteration     = 'Recent iterations';
$lang->program->ongoingStage      = 'Ongoing stage';
$lang->program->scrum             = 'Scrum';
$lang->program->waterfall         = 'CMMI';
$lang->program->waterfallTitle    = 'CMMI';
$lang->program->cannotCreateChild = 'It is not empty, so you cannot add a child. You can add a parent for it, and then create a child.';
$lang->program->hasChildren       = 'It has child programs or projects. You cannot delete it.';
$lang->program->confirmDelete     = "Do you want to delete [%s]?";
$lang->program->emptyPM           = 'No manager';
$lang->program->cannotChangeToCat = "It is not empty, so you cannot change it to a parent.";
$lang->program->cannotCancelCat   = "It has child projects, so you cannot unmark the parent.";
$lang->program->parentBeginEnd    = "Parent begin&end date: %s ~ %s";
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
$lang->program->PRJBeginGreateChild  = "The minimum start date of the project set: %s. The start date of the project cannot be less than the minimum start date of the program set.";
$lang->program->PRJEndLetterChild    = "The maximum finish date for the project set: %s. The completion date of a project cannot be greater than the maximum completion date of the program set.";
$lang->program->PRJChildLongTime     = "There are long-term projects in the child project, and the parent project should also be a long-term project.";

/* Actions. */
$lang->program->PGMCommon            = 'Program';
$lang->program->PGMIndex             = 'Home';
$lang->program->PGMCreate            = 'Create Program';
$lang->program->PGMCreateGuide       = 'Select Template';
$lang->program->PGMEdit              = 'Edit Program';
$lang->program->PGMBrowse            = 'Programs';
$lang->program->PGMProduct           = 'Products';
$lang->program->PGMProject           = 'Program Project List';
$lang->program->PGMAll               = 'All Programs';
$lang->program->PGMStart             = 'Start';
$lang->program->PGMFinish            = 'Finish';
$lang->program->PGMSuspend           = 'Suspend';
$lang->program->PGMDelete            = 'Delete';
$lang->program->PGMClose             = 'Close';
$lang->program->PGMActivate          = 'Activate';
$lang->program->PGMExport            = 'Export';
$lang->program->PGMStakeholder       = 'Stakeholder';
$lang->program->createStakeholder    = 'Create Stakeholder';
$lang->program->unlinkStakeholder    = 'Unlink Stakeholder';
$lang->program->stakeholderType      = 'Stakeholder type';
$lang->program->isStakeholderKey     = 'Key stakeholder';
$lang->program->importStakeholder    = 'Import program';
$lang->program->PGMManageMembers     = 'Program Team';
$lang->program->PGMParentBudget      = "Parent program budget：%s";
$lang->program->beyondParentBudget   = 'The remaining budget of the owned program has been exceeded.';
$lang->program->PGMBeginLetterParent = "Parent begin date: %s, begin date should be >= parent begin date.";
$lang->program->PGMEndGreaterParent  = "Parent end date: %s, end date should be <= parent end date.";
$lang->program->PGMBeginGreateChild  = "Child earliest begin: %s, parent begin date <= child earliest begin date.";
$lang->program->PGMEndLetterChild    = "Child latest end: %s, parent end date >= child latest end date.";

$lang->program->stakeholderTypeList['inside']  = 'Inside';
$lang->program->stakeholderTypeList['outside'] = 'Outside';

/* Fields. */
$lang->program->PGMName      = 'Name';
$lang->program->PGMTemplate  = 'Template';
$lang->program->PGMCategory  = 'Type';
$lang->program->PGMDesc      = 'Description';
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
$lang->program->PGMShowClosed = 'Closed programs.';
$lang->program->PGMTips       = 'If a parent item set is selected, products under that parent item set can be associated. If no item set is selected, a product with the same name as the item is created by default and associated with that item.';
$lang->program->PGMChangeTips = 'After modifying the parent program set, the products associated with the project will be cleared, and the requirements, bugs and other data under the project will also be affected, is it modified?';

$lang->program->PGMAclList['private'] = "Private (accessible to project portfolio holders and stakeholders, stakeholders can follow up maintenance)";
$lang->program->PGMAclList['open']    = "Default(user who can visit the program can access it)";

$lang->program->subPGMAclList['private'] = "Private (accessible to this program set leader and stakeholders, stakeholders can follow up maintenance)";
$lang->program->subPGMAclList['open']    = "Default(user who can visit the program can access it)";
$lang->program->subPGMAclList['program'] = "Open within the program (accessible to all higher-level program directors and affiliates, as well as to this program director and affiliates)";

$lang->program->PGMAuthList['extend'] = 'Inherit(program privileges and company privileges)';
$lang->program->PGMAuthList['reset']  = 'Reset(program privileges only)';

$lang->program->PGMFeatureBar['all'] = 'All';
