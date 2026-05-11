<?php
$lang->branch->common = 'Branch';
$lang->branch->manage = 'Manage Branch';
$lang->branch->sort   = 'Sort Branch';
$lang->branch->delete = 'Delete Branch';
$lang->branch->add    = 'Add';

$lang->branch->manageTitle = '%s Management';
$lang->branch->all         = 'All ';
$lang->branch->main        = 'Main';

$lang->branch->edit              = 'Edit %s';
$lang->branch->editAction        = 'Edit Branch';
$lang->branch->activate          = 'Activate';
$lang->branch->activateAction    = 'Activate Branch';
$lang->branch->close             = 'Close';
$lang->branch->closeAction       = 'Close Branch';
$lang->branch->create            = 'Create %s';
$lang->branch->createAction      = 'Create Branch';
$lang->branch->merge             = 'Merge';
$lang->branch->batchEdit         = 'Batch Edit';
$lang->branch->defaultBranch     = 'Default Branch';
$lang->branch->setDefault        = 'Set as Default';
$lang->branch->setDefaultAction  = 'Set Default Branch';
$lang->branch->mergeTo           = 'Merge to';
$lang->branch->mergeBranch       = 'Merge branch';
$lang->branch->mergeBranchAction = 'Merge branch';

$lang->branch->id          = 'ID';
$lang->branch->product     = $lang->productCommon;
$lang->branch->name        = 'Name';
$lang->branch->status      = 'Status';
$lang->branch->createdDate = 'Created on';
$lang->branch->closedDate  = 'Closed on';
$lang->branch->desc        = 'Description';
$lang->branch->order       = 'Sort';
$lang->branch->deleted     = 'Deleted';
$lang->branch->closed      = 'Closed';
$lang->branch->default     = 'Default';

$lang->branch->confirmDelete     = 'Are you sure you want to delete this @branch@?';
$lang->branch->confirmSetDefault = 'Are you sure you want to set this @branch@ as the default? Once set, plans and releases will default to this @branch@.';
$lang->branch->canNotDelete      = 'This @branch@ contains data and cannot be deleted.';
$lang->branch->nameNotEmpty      = 'Name is required.';
$lang->branch->confirmClose      = 'Are you sure you want to close this @branch@?';
$lang->branch->confirmActivate   = 'Are you sure you want to activate this @branch@?';
$lang->branch->existName         = '@branch@ name already exists.';
$lang->branch->mergedMain        = 'Trunk cannot be merged.';
$lang->branch->mergeTips         = 'After merging, all releases, plans, builds, modules, stories, bugs, and test cases under this @branch@ will be moved to the target @branch@.';
$lang->branch->targetBranchTips  = 'You can merge it into an existing @branch@, into trunk, or create a new @branch@.';
$lang->branch->confirmMerge      = 'Data from mergedBranch will be merged into targetBranch. Are you sure you want to proceed? This action cannot be undone.';

$lang->branch->noData     = 'No branches yet.';
$lang->branch->mainBranch = "{$lang->productCommon} default trunk: %s.";

$lang->branch->statusList = array();
$lang->branch->statusList['active'] = 'Activate';
$lang->branch->statusList['closed'] = 'Closed';

$lang->branch->featureBar['manage']['all']    = 'All';
$lang->branch->featureBar['manage']['active'] = 'Activate';
$lang->branch->featureBar['manage']['closed'] = 'Closed';
