<?php
$lang->gogs->common        = 'Gogs';
$lang->gogs->browse        = 'Gogs Browse';
$lang->gogs->search        = 'Search';
$lang->gogs->create        = 'Create Gogs';
$lang->gogs->edit          = 'Edit Gogs';
$lang->gogs->view          = 'Gogs Details';
$lang->gogs->delete        = 'Delete Gogs';
$lang->gogs->confirmDelete = 'Do you want to delete this Gogs server?';
$lang->gogs->bindUser      = 'Bind User';
$lang->gogs->gogsAvatar    = 'Avatar';
$lang->gogs->gogsAccount   = 'Gogs Account';
$lang->gogs->gogsEmail     = 'Email';
$lang->gogs->zentaoAccount = 'Zentao Account';
$lang->gogs->bindingStatus = 'Binding Status';
$lang->gogs->all           = 'All';
$lang->gogs->notBind       = 'Not bind';
$lang->gogs->binded        = 'Binded';
$lang->gogs->bindDynamic   = '%s and Zentao user %s';
$lang->gogs->bindedError   = 'The bound user has been deleted or modified. Please bind again.';
$lang->gogs->zentaoEmail   = 'Zentao User\'s Email';
$lang->gogs->accountDesc   = '(Automatically match users with the same email)';

$lang->gogs->bindStatus['binded']      = $lang->gogs->binded;
$lang->gogs->bindStatus['notBind']     = "<span class='text-danger'>{$lang->gogs->notBind}</span>";
$lang->gogs->bindStatus['bindedError'] = "<span class='text-danger'>{$lang->gogs->bindedError}</span>";

$lang->gogs->browseAction = 'Gogs List';
$lang->gogs->deleteAction = 'Delete Gogs';

$lang->gogs->id    = 'ID';
$lang->gogs->name  = "Server Name";
$lang->gogs->url   = 'Server URL';
$lang->gogs->token = 'Token';

$lang->gogs->tokenLimit    = "The current token has no admin privilege. Please regenerate one with root user in Gogs.";
$lang->gogs->hostError     = "So the current Gogs server address is invalid, please confirm that the current server can be accessed and try again.";
$lang->gogs->bindUserError = "Can not bind users repeatedly %s";

$lang->gogs->server        = "Server List";
$lang->gogs->lblCreate     = 'Create Gogs Server';
$lang->gogs->emptyError    = " cannot be empty";
$lang->gogs->createSuccess = "Create success";
