<?php
$lang->branch->common = 'Branch';
$lang->branch->manage = 'Manage Branch';
$lang->branch->sort   = 'Rank Branch';
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
$lang->branch->setDefault        = 'Set Default';
$lang->branch->setDefaultAction  = 'Set Default';
$lang->branch->mergeTo           = 'Merge to';
$lang->branch->mergeBranch       = 'Merge branch';
$lang->branch->mergeBranchAction = 'Merge branch';

$lang->branch->id          = 'ID';
$lang->branch->product     = $lang->productCommon;
$lang->branch->name        = 'Name';
$lang->branch->status      = 'Status';
$lang->branch->createdDate = 'Created Date';
$lang->branch->closedDate  = 'Closed Date';
$lang->branch->desc        = 'Desc';
$lang->branch->order       = 'Order';
$lang->branch->deleted     = 'Delete';
$lang->branch->closed      = 'Closed';
$lang->branch->default     = 'Default';

$lang->branch->confirmDelete     = 'Do you want to delete this @branch@?';
$lang->branch->confirmSetDefault = 'Do you want to set @branch@ as default @branch@? After your setting, the default @branch@ will be selected and shown in its Plan/Release list.';
$lang->branch->canNotDelete      = 'There is data in @branch@. It cannot be deleted.';
$lang->branch->nameNotEmpty      = 'Name must not be empty！';
$lang->branch->confirmClose      = 'Do you want to close this @branch@？';
$lang->branch->confirmActivate   = 'Do you want to activate this @branch@？';
$lang->branch->existName         = '@branch@ name already exists.';
$lang->branch->mergedMain        = 'Trunk does not support being merged.';
$lang->branch->mergeTips         = 'After the branch is merged, the corresponding releases, plans, builds, modules, requirements, bugs, and cases under the branch will be merged into the new branch.';
$lang->branch->targetBranchTips  = 'You can merge it into an existing branch, merge it into the trunk, or create a new branch.';
$lang->branch->confirmMerge      = 'The data of "mergedBranch" will be merged into "targetBranch", please confirm whether you want to perform the branch merge operation, the data will not be restored after the merge!';

$lang->branch->noData     = 'No branches.';
$lang->branch->mainBranch = "The default main %s of the {$lang->productCommon}.";

$lang->branch->statusList = array();
$lang->branch->statusList['active'] = 'Active';
$lang->branch->statusList['closed'] = 'Closed';

$lang->branch->featureBar['manage']['all']    = 'All';
$lang->branch->featureBar['manage']['active'] = 'Active';
$lang->branch->featureBar['manage']['closed'] = 'Closed';
