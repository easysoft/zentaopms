<?php
$lang->gitlab = new stdclass;
$lang->gitlab->common        = 'Gitlab';
$lang->gitlab->browse        = 'gitlab';
$lang->gitlab->create        = 'Create Gitlab';
$lang->gitlab->edit          = 'Edit Gitlab';
$lang->gitlab->bindUser      = 'Bind User';
$lang->gitlab->webhook       = 'webhook';
$lang->gitlab->bindProduct   = 'Import Product';
$lang->gitlab->importIssue   = 'Import Issue';
$lang->gitlab->delete        = 'Delete';
$lang->gitlab->confirmDelete = 'Do you want to delete this Gitlab server?';
$lang->gitlab->gitlabAccount = 'Gitlab Account';
$lang->gitlab->zentaoAccount = 'Zentao Account';

$lang->gitlab->browseAction  = 'Gitlab List';
$lang->gitlab->deleteAction  = 'Delete Gitlab';
$lang->gitlab->gitlabProject = "{$lang->gitlab->common}project";
$lang->gitlab->gitlabIssue   = "{$lang->gitlab->common}issue";
$lang->gitlab->zentaoProduct = 'Zentao Product';
$lang->gitlab->objectType    = 'Type'; // task, bug, story

$lang->gitlab->id             = 'ID';
$lang->gitlab->name           = "{$lang->gitlab->common}name";
$lang->gitlab->url            = 'Gitlab Url';
$lang->gitlab->token          = 'Token';
$lang->gitlab->defaultProject = 'Default Project';
$lang->gitlab->private        = 'MD5 Verify';

$lang->gitlab->lblCreate  = 'Create Gitlab Server';
$lang->gitlab->desc       = 'Description';
$lang->gitlab->tokenFirst = 'When the Token is not empty, the Token will be used first';
$lang->gitlab->tips       = 'When using a password, please disable the "Prevent cross-site request forgery" option in the GitLab global security settings.';

$lang->gitlab->placeholder = new stdclass;
$lang->gitlab->placeholder->name  = '';
$lang->gitlab->placeholder->url   = "Please fill in the access address of the Gitlab Server homepage, as :https://gitlab.zentao.net.";
$lang->gitlab->placeholder->token = "Please fill in the access token of an account with admin privileges.";

$lang->gitlab->noImportableIssues = "There are currently no issues available for import.";
$lang->gitlab->tokenError         = "The current token is not admin rights.";
$lang->gitlab->hostError          = "Invalid Gitlab service address.";
$lang->gitlab->bindUserError      = "Cannot bind users repeatedly %s";
$lang->gitlab->importIssueError   = "The execution to which this issue belongs is not selected.";
$lang->gitlab->importIssueWarn    = "There is a problem of import failure, you can try to import again.";

