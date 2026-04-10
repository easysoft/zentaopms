<?php
$lang->repobranchtype->common        = 'Branch Type';
$lang->repobranchtype->browse        = 'Browse Branch Type';
$lang->repobranchtype->create        = 'Create Branch Type';
$lang->repobranchtype->edit          = 'Edit Branch Type';
$lang->repobranchtype->delete        = 'Delete Branch Type';
$lang->repobranchtype->import        = 'Import Branch Type';
$lang->repobranchtype->setBranchRule = 'Set Review Flow';

$lang->repobranchtype->name     = 'Name';
$lang->repobranchtype->key      = 'Key';
$lang->repobranchtype->prefixes = 'Prefixes';
$lang->repobranchtype->desc     = 'Description';

$lang->repobranchtype->placeholder      = new stdclass();
$lang->repobranchtype->placeholder->key = 'Unique identifier for branch rules, must start with a letter';

$lang->repobranchtype->tips = new stdclass();
$lang->repobranchtype->tips->maxPrefixes    = 'Max 5 prefixes';
$lang->repobranchtype->tips->minPrefixes    = 'Min 1 prefix';
$lang->repobranchtype->tips->prefixRequired = 'At least 1 prefix is required';
$lang->repobranchtype->tips->createSuccess  = 'Branch type created successfully';
$lang->repobranchtype->tips->updateSuccess  = 'Branch type updated successfully';
$lang->repobranchtype->tips->importSuccess  = 'Branch type imported successfully';

$lang->repobranchtype->error = new stdclass();
$lang->repobranchtype->error->keyFormat       = 'Key format is incorrect, must start with a letter and contain only letters, numbers, and symbols (/-_.)';
$lang->repobranchtype->error->prefixFormat    = 'Prefix format is incorrect, must contain only letters, numbers, and symbols (/-_.)';
$lang->repobranchtype->error->prefixSlash     = 'Prefix must contain only one slash';
$lang->repobranchtype->error->prefixDuplicate = 'Prefix cannot be duplicated';
$lang->repobranchtype->error->notExists       = 'Branch type does not exist';

$lang->repobranchtype->notice = new stdclass();
$lang->repobranchtype->notice->delete                     = 'Are you sure you want to delete this branch type?';
$lang->repobranchtype->notice->noPermissionToCreateBranch = 'No permission to create branch';
$lang->repobranchtype->notice->noPermissionToDeleteBranch = 'No permission to delete branch';
