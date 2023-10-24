<?php
$lang->branch->common = 'Etablissements';
$lang->branch->manage = 'Gérer Etablissements';
$lang->branch->sort   = 'Classer Etablissement';
$lang->branch->delete = 'Supprimer Etablissement';
$lang->branch->add    = 'Ajout';

$lang->branch->manageTitle = 'Gestion %s';
$lang->branch->all         = 'Tous';
$lang->branch->main        = 'Main';

$lang->branch->edit              = 'Edit';
$lang->branch->editAction        = 'Edit Branch';
$lang->branch->activate          = 'Activate';
$lang->branch->activateAction    = 'Activate Branch';
$lang->branch->close             = 'Close';
$lang->branch->closeAction       = 'Close Branch';
$lang->branch->create            = 'Create Branch';
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
$lang->branch->name        = 'Nom';
$lang->branch->status      = 'Status';
$lang->branch->createdDate = 'Created Date';
$lang->branch->closedDate  = 'Closed Date';
$lang->branch->desc        = 'Desc';
$lang->branch->order       = 'Ordre';
$lang->branch->deleted     = 'Delete';
$lang->branch->closed      = 'Closed';
$lang->branch->default     = 'Default';

$lang->branch->confirmDelete     = "Voulez-vous vraiment supprimer l'établissement @branch@ ?";
$lang->branch->confirmSetDefault = 'Do you want to set @branch@ as default @branch@? After your setting, the default @branch@ will be selected and shown in its Plan/Release list.';
$lang->branch->canNotDelete      = 'Attention ! Il y a des données dans @branch@. Suppression impossible !';
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
